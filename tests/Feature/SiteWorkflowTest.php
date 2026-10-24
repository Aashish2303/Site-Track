<?php

namespace Tests\Feature;

use App\Models\DailyProgress;
use App\Models\Project;
use App\Models\ProjectBoq;
use App\Models\User;
use App\Services\SiteReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class SiteWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    private Project $site;
    private Project $otherSite;
    private ProjectBoq $boq;
    private ProjectBoq $otherBoq;
    private User $supervisor;
    private User $engineer;

    protected function setUp(): void
    {
        parent::setUp();
        if (!in_array(config('database.connections.mysql.database'), ['sitetrack_test', 'sitetrack_ci'], true)) {
            throw new \RuntimeException('Workflow tests require an isolated sitetrack_test or sitetrack_ci database.');
        }
        Carbon::setTestNow('2026-10-24 12:00:00');
        $this->assertTrue(app(\App\Services\WorkflowSchema::class)->ready(), 'Run the additive workflow migration on the isolated test database.');
        $prototype = Project::withoutGlobalScopes()->firstOrFail();
        $this->site = $prototype->replicate(); $this->site->project_name = 'Workflow site A'; $this->site->save();
        $this->otherSite = $prototype->replicate(); $this->otherSite->project_name = 'Workflow site B'; $this->otherSite->save();
        $boq = ProjectBoq::withoutGlobalScopes()->firstOrFail();
        $this->boq = $boq->replicate();
        $this->boq->fill(['pboq_project' => $this->site->project_id, 'pboq_qty' => 10, 'pboq_rate' => 100, 'pboq_amount' => 1000])->save();
        $this->otherBoq = $boq->replicate(); $this->otherBoq->pboq_project = $this->otherSite->project_id; $this->otherBoq->save();
        $admin = User::where('user_role', 1)->firstOrFail();
        $this->supervisor = $admin->replicate(); $this->supervisor->user_lname = 'supervisor-'.Str::random(8); $this->supervisor->save();
        $this->engineer = $admin->replicate(); $this->engineer->user_lname = 'engineer-'.Str::random(8); $this->engineer->save();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function selected(User $user = null): static
    {
        return $this->actingAs($user ?? $this->supervisor)->withSession(['workspace_project_id' => $this->site->project_id]);
    }

    private function progressPayload(array $overrides = []): array
    {
        $prototype = DailyProgress::withoutGlobalScopes()->firstOrFail();
        return array_replace([
            'submit' => 'submit', 'dprogress_date' => '2026-10-24', 'dprogress_project' => $this->site->project_id,
            'dprogress_category' => $prototype->dprogress_category, 'dprogress_description' => 'Verified concrete pour',
            'dprogress_boq' => $this->boq->pboq_id, 'dprogress_qty' => 4, 'dprogress_location' => 'Block A',
            'dprogress_subcontractor' => $prototype->dprogress_subcontractor,
            'dprogress_incharge' => $this->supervisor->user_id, 'dprogress_cforward' => 2,
        ], $overrides);
    }

    public function test_project_selection_is_required_and_queries_and_writes_stay_in_that_project(): void
    {
        $this->actingAs($this->supervisor)->get('/user/daily_works')->assertRedirect(route('user.projects'));
        $this->postJson('/user/get_daily_progress')->assertStatus(409);
        $this->post('/user/workspace/select', ['project_id' => $this->site->project_id])->assertRedirect(route('user.project_workspace'));
        $this->assertEquals($this->site->project_id, session('workspace_project_id'));
        $this->get('/user/workspace')->assertOk()->assertSee('Workflow site A');
        $this->postJson('/user/get_daily_progress', $this->progressPayload(['dprogress_project' => $this->otherSite->project_id]))->assertForbidden();
        $this->post('/user/add_daily_progress', $this->progressPayload(['dprogress_boq' => $this->otherBoq->pboq_id]))
            ->assertSessionHasErrors('dprogress_boq');
        $this->post('/user/add_daily_progress', $this->progressPayload())->assertRedirect();
        $this->withHeader('X-Requested-With', 'XMLHttpRequest')->postJson('/user/get_daily_progress')->assertOk()->assertJsonPath('recordsTotal', 1)
            ->assertJsonPath('data.0.project_name', 'Workflow site A');
        $this->withHeader('X-Requested-With', '');
        $this->get('/user/reports?project=')->assertOk()->assertSee('All projects');
        $this->assertEquals($this->site->project_id, session('workspace_project_id'), 'Global reports must not change the selected project.');
    }

    public function test_global_pages_and_project_pages_have_separate_navigation_even_when_a_site_is_remembered(): void
    {
        $global = ['Dashboard', 'Projects Directory', 'Subcon Ledger', 'Vendor Ledger', 'Reports', 'Vendor Directory', 'Administration'];
        foreach (['/user/projects', '/user/dashboard', '/user/reports'] as $path) {
            $response = $this->selected()->get($path)->assertOk()->assertSee('data-navigation="global"', false);
            $this->assertSame($global, $this->navigationLabels($response->getContent()));
            $response->assertDontSee('class="st-project-context"', false);
        }
        foreach (['/user/workspace', '/user/daily_works', '/user/daily_progress', '/user/site-materials', '/user/labour_trackers'] as $path) {
            $response = $this->get($path)->assertOk()->assertSee('data-navigation="project"', false);
            $labels = $this->navigationLabels($response->getContent());
            $this->assertContains('Back to global overview', $labels);
            $this->assertContains('Project overview', $labels);
            $this->assertContains('DWR · Daily work', $labels);
            $this->assertNotContains('Dashboard', $labels);
            $this->assertNotContains('Reports', $labels);
            $this->assertNotContains('Administration', $labels);
            $response->assertSee('Workflow site A')->assertSee('class="st-project-context"', false);
        }
        $this->get('/user/projects')->assertOk()->assertSee('data-navigation="global"', false);
        $this->assertEquals($this->site->project_id, session('workspace_project_id'));
        $this->post('/user/workspace/select', ['project_id' => $this->otherSite->project_id])->assertRedirect(route('user.project_workspace'));
        $this->get('/user/workspace')->assertOk()->assertSee('Workflow site B');
        $this->post('/user/workspace/leave')->assertRedirect(route('user.projects'));
        $this->assertNull(session('workspace_project_id'));
        $this->get('/user/daily_works')->assertRedirect(route('user.projects'));
    }

    private function navigationLabels(string $html): array
    {
        $previous = libxml_use_internal_errors(true);
        try {
            $dom = new \DOMDocument(); $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_NONET);
            $xpath = new \DOMXPath($dom);
            $nodes = $xpath->query('//*[@id="sidebar-menu"]/ul/li/a/span[not(contains(@class,"menu-arrow"))]');
            $labels = [];
            foreach ($nodes as $node) { $labels[] = trim($node->textContent); }
            return $labels;
        } finally {
            libxml_clear_errors(); libxml_use_internal_errors($previous);
        }
    }

    public function test_quantities_require_independent_approval_and_corrections_preserve_the_previous_decision(): void
    {
        Storage::fake('evidence');
        $this->selected()->post('/user/add_daily_progress', $this->progressPayload([
            'photos' => [UploadedFile::fake()->image('site.jpg')],
        ]))->assertRedirect();
        $entry = DailyProgress::where('dprogress_project', $this->site->project_id)->firstOrFail();
        $this->assertDatabaseHas('progress_reviews', ['progress_id' => $entry->dprogress_id, 'state' => 'pending', 'approved_qty' => 0]);
        $photo = DB::table('progress_photos')->where('progress_id', $entry->dprogress_id)->first();
        Storage::disk('evidence')->assertExists($photo->path);
        $this->get('/user/progress/'.$entry->dprogress_id.'/photos/'.$photo->id)->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        $decision = ['decision' => 'approved', 'approved_qty' => 3, 'revision' => 1, 'note' => 'Measured on site'];
        $this->post('/user/progress/'.$entry->dprogress_id.'/review', $decision)->assertForbidden();
        $this->selected($this->engineer)->post('/user/progress/'.$entry->dprogress_id.'/review', $decision)->assertRedirect();
        $this->assertDatabaseHas('progress_reviews', ['progress_id' => $entry->dprogress_id, 'state' => 'approved', 'approved_qty' => 3]);
        $this->assertEquals(3, \App\Helpers\Helper::doneBoq($this->site->project_id)[$this->boq->pboq_id]);
        $this->selected()->post('/user/edit_daily_progress/'.$entry->dprogress_id, $this->progressPayload([
            'dprogress_qty' => 6, 'revision' => 1, 'correction_note' => 'Revised measurement after inspection',
        ]))->assertRedirect();
        $this->assertDatabaseHas('progress_reviews', ['progress_id' => $entry->dprogress_id, 'state' => 'pending', 'revision' => 2, 'approved_qty' => 0]);
        $this->selected($this->engineer)->post('/user/progress/'.$entry->dprogress_id.'/review', $decision)->assertSessionHasErrors('revision');
        $this->post('/user/progress/'.$entry->dprogress_id.'/review', array_replace($decision, ['approved_qty' => 6, 'revision' => 2]))->assertRedirect();
        $this->assertEquals(4, DB::table('progress_events')->where('progress_id', $entry->dprogress_id)->count());
        $this->assertDatabaseHas('progress_events', ['progress_id' => $entry->dprogress_id, 'action' => 'corrected', 'revision' => 2]);
        $this->get('/user/progress/'.$entry->dprogress_id.'/review')->assertOk()->assertSee('Measured on site')->assertSee('Revised measurement');
        $this->withSession(['workspace_project_id' => $this->otherSite->project_id])->get('/user/progress/'.$entry->dprogress_id.'/photos/'.$photo->id)->assertNotFound();
    }

    public function test_material_report_rolls_opening_receipts_consumption_returns_and_cost_forward(): void
    {
        $material = DB::table('materials')->first(); $unit = $material->material_unit;
        foreach ([['2026-10-01', 200], ['2026-10-20', 100]] as [$date, $qty]) {
            DB::table('assign_materials')->insert($this->materialReceipt($material, $date, $qty));
        }
        $this->selected()->post('/user/site-materials/usage', ['material_id' => $material->material_id,
            'used_on' => '2026-10-21', 'quantity' => 125, 'unit_rate' => 420, 'submission_key' => (string) Str::uuid()])->assertRedirect();
        $rows = app(SiteReportService::class)->materials([$this->site->project_id], '2026-10-19', '2026-10-25');
        $this->assertCount(1, $rows);
        $this->assertEquals(['opening' => 200, 'received' => 100, 'used' => 125, 'returned' => 0, 'closing' => 175, 'cost' => 52500],
            array_intersect_key($rows[0], array_flip(['opening', 'received', 'used', 'returned', 'closing', 'cost'])));
        $this->post('/user/site-materials/usage', ['material_id' => $material->material_id,
            'used_on' => '2026-10-21', 'quantity' => 176, 'submission_key' => (string) Str::uuid()])->assertSessionHasErrors('quantity');
        $this->post('/user/site-materials/usage', ['material_id' => $material->material_id,
            'used_on' => '2026-09-30', 'quantity' => 1, 'submission_key' => (string) Str::uuid()])->assertSessionHasErrors('quantity');
        $this->get('/user/reports?project='.$this->site->project_id.'&date=2026-10-21&category=materials')->assertOk()->assertSee('52500.00');
    }

    private function materialReceipt(object $material, string $date, float $quantity): array
    {
        return ['amaterial_project' => $this->site->project_id, 'amaterial_material' => $material->material_id,
            'amaterial_category' => $material->material_category, 'amaterial_unit' => $material->material_unit,
            'amaterial_date' => $date, 'amaterial_qty' => $quantity, 'amaterial_status' => 1,
            'amaterial_added_on' => now(), 'amaterial_added_by' => $this->supervisor->user_id,
            'amaterial_updated_on' => now(), 'amaterial_updated_by' => $this->supervisor->user_id];
    }

    public function test_wages_are_calculated_on_the_server_and_advances_are_not_counted_twice(): void
    {
        $contractor = DB::table('subcontractors')->value('subcontractor_id');
        $category = DB::table('category')->value('category_id');
        $payload = ['submit' => 'submit', 'ltracker_project' => $this->site->project_id,
            'ltracker_date' => '2026-10-21', 'ltracker_subcontractor' => $contractor,
            'ltracker_incharge' => $this->supervisor->user_id, 'ltracker_shift' => 1,
            'ltracker_in' => '08:00', 'ltracker_out' => '18:00', 'grand_total' => 1,
            'workers' => ['CATEGORY' => [1 => $category], 'SKILL' => [1 => 1], 'MALE' => [1 => 2], 'FEMALE' => [1 => 1],
                'WAGE' => [1 => '500.00'], 'OVERTIME_HOURS' => [1 => 2], 'OVERTIME_RATE' => [1 => 100], 'TOTAL' => [1 => 1]]];
        $this->selected()->post('/user/add_labour_tracker', $payload)->assertRedirect(route('user.labour_trackers'));
        $this->assertDatabaseHas('labour_trackers', ['ltracker_project' => $this->site->project_id, 'ltracker_total' => 2100, 'ltracker_thours' => 10]);
        $key = (string) Str::uuid();
        $payment = ['subcontractor_id' => $contractor, 'category_id' => $category, 'paid_on' => '2026-10-21',
            'type' => 'advance', 'amount' => 500, 'reference' => 'TEST-ADVANCE', 'submission_key' => $key];
        $this->post('/user/labour-payments', $payment)->assertRedirect();
        $this->post('/user/labour-payments', $payment)->assertRedirect();
        $this->post('/user/labour-payments', array_replace($payment, ['amount' => 700]))->assertStatus(409);
        $this->assertEquals(1, DB::table('labour_payments')->where('submission_key', $key)->count());
        $this->post('/user/labour-payments', array_replace($payment, ['type' => 'payment', 'amount' => 1000,
            'reference' => 'TEST-PAYMENT', 'submission_key' => (string) Str::uuid()]))->assertRedirect();
        $rows = app(SiteReportService::class)->labour([$this->site->project_id], '2026-10-19', '2026-10-25');
        $this->assertCount(1, $rows);
        $this->assertEquals(['attendance' => 3, 'base' => 1500, 'overtime' => 600, 'advance' => 500, 'paid' => 1000, 'balance' => 600],
            array_intersect_key($rows[0], array_flip(['attendance', 'base', 'overtime', 'advance', 'paid', 'balance'])));
        $next = app(SiteReportService::class)->labour([$this->site->project_id], '2026-10-26', '2026-11-01');
        $this->assertEquals(600, $next[0]['opening']); $this->assertEquals(600, $next[0]['balance']);
        $this->get('/user/labour-payments?week=2026-10-21')->assertOk()->assertSee('TEST-PAYMENT');
    }

    public function test_report_exports_produce_real_excel_and_pdf_files_with_safe_project_names(): void
    {
        $material = DB::table('materials')->first();
        DB::table('assign_materials')->insert($this->materialReceipt($material, '2026-10-21', 50));
        $this->site->project_name = '=1+1'; $this->site->save();
        $url = '/user/reports?project='.$this->site->project_id.'&date=2026-10-21&category=materials&format=';
        $response = $this->selected()->get($url.'xlsx')->assertOk();
        $book = IOFactory::load($response->baseResponse->getFile()->getPathname());
        $sheet = $book->getActiveSheet(); $dataRow = null;
        for ($row = 1; $row <= $sheet->getHighestRow(); $row++) {
            if ($sheet->getCell('A'.$row)->getValue() === '=1+1') { $dataRow = $row; break; }
        }
        $this->assertNotNull($dataRow);
        $this->assertEquals('s', $sheet->getCell('A'.$dataRow)->getDataType());
        $this->assertEquals(50, $sheet->getCell('E'.$dataRow)->getValue());
        $book->disconnectWorksheets();
        $pdf = $this->get($url.'pdf')->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $pdf->getContent());
        $this->assertGreaterThan(1000, strlen($pdf->getContent()));
    }

    public function test_new_financial_and_approval_capabilities_deny_roles_without_the_specific_permission(): void
    {
        $this->supervisor->user_role = 999999; $this->supervisor->save();
        $this->selected()->get('/user/reports')->assertForbidden();
        $this->post('/user/labour-payments', [])->assertForbidden();
        $this->post('/user/site-materials/usage', [])->assertForbidden();
        $this->post('/user/progress/1/review', [])->assertForbidden();
    }

    public function test_an_unactivated_database_shows_setup_instead_of_querying_missing_workflow_tables(): void
    {
        DB::table('site_workflow_installations')->where('version', 1)->delete();
        app()->forgetInstance(\App\Services\WorkflowSchema::class);
        $this->selected()->get('/user/projects')->assertOk();
        $this->get('/user/reports')->assertStatus(503)->assertSee('awaiting activation');
        $this->get('/user/site-materials')->assertStatus(503)->assertSee('awaiting activation');
        $this->get('/user/labour-payments')->assertStatus(503)->assertSee('awaiting activation');
    }

    public function test_existing_quotation_actions_resolve_quotation_ids_and_respect_the_selected_site(): void
    {
        $prototype = \App\Models\Indent::withoutGlobalScopes()->firstOrFail();
        $own = $prototype->replicate(); $own->indent_project = $this->site->project_id; $own->save();
        $foreign = $prototype->replicate(); $foreign->indent_project = $this->otherSite->project_id; $foreign->save();
        $quote = \App\Models\IndentQuotation::withoutGlobalScopes()->firstOrFail();
        $ownQuote = $quote->replicate(); $ownQuote->iquote_indent = $own->indent_id; $ownQuote->iquote_status = 1; $ownQuote->save();
        if ((int) $ownQuote->iquote_id === (int) $own->indent_id) {
            $ownQuote = $quote->replicate(); $ownQuote->iquote_indent = $own->indent_id; $ownQuote->iquote_status = 1; $ownQuote->save();
        }
        $otherQuote = $quote->replicate(); $otherQuote->iquote_indent = $foreign->indent_id; $otherQuote->iquote_status = 1; $otherQuote->save();
        $this->selected()->get('/user/status_quotation/'.$ownQuote->iquote_id.'/2')->assertRedirect();
        $this->assertDatabaseHas('indent_quotations', ['iquote_id' => $ownQuote->iquote_id, 'iquote_status' => 2]);
        $this->get('/user/status_quotation/'.$otherQuote->iquote_id.'/2')->assertNotFound();
        $this->assertDatabaseHas('indent_quotations', ['iquote_id' => $otherQuote->iquote_id, 'iquote_status' => 1]);
    }
}
