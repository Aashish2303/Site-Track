# Project workspaces, reports, verification and labour payments

This release changes application behavior and adds database tables. The Hostinger Git integration must deploy the full application and install the locked Composer dependencies, including Dompdf for PDF exports. The older `scripts/deploy-ui.sh` uploader only copies presentation files and must not be used for this release. Its GitHub deployment job remains disabled.

## Activate the database update once

Back up the existing production database and site files in Hostinger first. Keep the existing `.env`, application key, database assignment, uploaded files and private storage. This release does not require a database replacement or an application-key change.

Choose **one** activation method:

1. In the Laravel application directory containing `artisan`, run:

   ```sh
   php artisan migrate --path=database/migrations/workflows --force
   php artisan view:clear
   ```

2. Without SSH, open the existing SiteTrack database in Hostinger's phpMyAdmin, choose **Import**, and upload **`deploy/site-workflows.sql`** from this release. This is an additive patch, not the original `database.sql` dump. Confirm that the import finishes without errors. The patch and Artisan migration are designed to preserve existing data when rerun. After SQL activation, a later Artisan run will safely register the same migration.

Before activation the project directory and existing site modules remain available. New reporting, consumption, approvals and payment pages show an activation notice instead of querying nonexistent tables. Historic progress is tagged unverified; it is never silently converted to approved progress.

## Permissions

The migration adds these permissions and initially grants them only to administrator role 1. In **Administration → Roles & permissions**, grant only the capabilities each role needs:

- Daily Progress → **Approve reported quantities**: engineers authorised to verify work. Submitters and the latest editor cannot approve their own entry, including administrators.
- QMS Reports → **Site reports** and **Export site reports**: reports across projects, including financial information.
- Assign Materials → **Record site consumption**.
- Labour Tracker → **View labour payment review** and **Record labour payments and advances**.

Existing project/module permissions still apply. The selected project is stored in the login session; project-specific queries, exports and writes remain restricted to that site. Switching sites uses a CSRF-protected POST. Global reports and global contractor/vendor ledgers keep their all-project view without changing the selected site. Shared material catalogue, central purchase orders and central stores remain company-wide reference data, accessed after selecting a project.

Global pages show only the six overview links and Administration. Opening a project routes to its workspace and replaces that menu with the site's operational links. The selected-project banner appears only inside site pages. **Back to global overview** returns to the project directory while remembering the selected site; **Switch project** clears the selection so another site can be chosen. Global Reports defaults to all projects unless an explicit project filter is supplied.

## Data meanings

- Material assignments are **receipts into the site**, not consumption. Record actual usage in **Site materials**. Opening stock includes all earlier receipts minus returns and usage; closing stock is opening + received − returned − used. Separate units are never combined. Cost uses the rate supplied on the usage entry; missing rates are shown as not fully priced. Backdated usage and returns cannot make a later recorded site balance negative.
- Labour attendance retains the existing **crew-by-trade** structure. It does not pretend to identify individual workers. Attendance counts worker-shifts. Daily wage × attending workers gives base wages; overtime is entered as hours per worker × hourly rate × attending workers. Server calculations override submitted totals. Weekly balances carry earlier wages, advances and payments forward. Negative balances represent prepaid credit.
- Existing subcontractor-ledger payments are not automatically classified as labour wages. Enter historic labour payments in the new ledger with their original dates and references before settling a balance. New labour payments are not also posted into the old contractor ledger, preventing double counting. Payment records are append-only and repeat submissions use unique keys. A mistaken cash entry should be reconciled by an administrator, not silently deleted.
- DPR quantities are reported separately from approved quantities. A correction or work-status change creates a new revision, clears current approval and retains the previous decision in an append-only event history. Concurrent stale reviews are rejected. Quantities above BOQ are flagged; a review note is required. Engineer-approved quantity cannot exceed that submission's reported quantity.
- Photos are stored on the **evidence** disk under `storage/app/private`, served only through authenticated, project-scoped routes. Preserve this directory across deployments and include it in backups. The app labels the report date and the actual upload timestamp; it does not claim to verify the photo's capture time.

## Verify after activation

Sign in, choose a project, move between DWR/DPR/materials/labour, and confirm that the selected-site banner remains. Check the global reports project selector, day/week/month/custom dates, material/work-category filters and Excel/PDF downloads. Use separate authorised accounts to submit and review a progress entry. Test wages, a payment and an advance with agreed test data before recording real settlements.

Database migrations are forward-only so an application rollback cannot erase evidence, approvals or payments. If the application must be rolled back, retain the new tables and storage. No production rollback, data import, or migration is performed by the repository push itself.
