-- SiteTrack project workflows: additive database update only.
-- Import into the EXISTING SiteTrack database after taking a backup.
-- Does not drop tables, replace existing rows, import database.sql, or modify login credentials.
-- Safe to re-run; existing approvals and payments are retained.

CREATE TABLE IF NOT EXISTS `progress_reviews` (
  `progress_id` bigint(20) unsigned NOT NULL,
  `state` varchar(16) NOT NULL DEFAULT 'pending',
  `approved_qty` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `revision` int(10) unsigned NOT NULL DEFAULT 1,
  `reviewer_id` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `note` text DEFAULT NULL,
  PRIMARY KEY (`progress_id`),
  KEY `progress_reviews_state_index` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `progress_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `progress_id` bigint(20) unsigned NOT NULL,
  `actor_id` bigint(20) unsigned NOT NULL,
  `action` varchar(24) NOT NULL,
  `revision` int(10) unsigned NOT NULL,
  `before_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`before_data`)),
  `after_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`after_data`)),
  `note` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `progress_events_progress_id_index` (`progress_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `progress_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `progress_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime` varchar(40) NOT NULL,
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `uploaded_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `progress_photos_progress_id_index` (`progress_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `site_material_usage` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `material_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `used_on` date NOT NULL,
  `quantity` decimal(16,4) NOT NULL,
  `unit_rate` decimal(16,4) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `submission_key` char(36) NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_material_usage_submission_key_unique` (`submission_key`),
  KEY `site_material_usage_project_id_material_id_used_on_index` (`project_id`,`material_id`,`used_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `labour_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `subcontractor_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `paid_on` date NOT NULL,
  `type` varchar(16) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `reference` varchar(120) NOT NULL,
  `notes` text DEFAULT NULL,
  `submission_key` char(36) NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `labour_payments_submission_key_unique` (`submission_key`),
  KEY `labour_payment_account` (`project_id`,`subcontractor_id`,`category_id`,`paid_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `site_workflow_installations` (
  `version` int(10) unsigned NOT NULL,
  `installed_at` datetime NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @st_workflow_sql = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'labour_workers' AND COLUMN_NAME = 'lworker_overtime_hours') = 0, 'ALTER TABLE labour_workers ADD COLUMN lworker_overtime_hours DECIMAL(8,2) NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st_workflow_stmt FROM @st_workflow_sql;
EXECUTE st_workflow_stmt;
DEALLOCATE PREPARE st_workflow_stmt;

SET @st_workflow_sql = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'labour_workers' AND COLUMN_NAME = 'lworker_overtime_rate') = 0, 'ALTER TABLE labour_workers ADD COLUMN lworker_overtime_rate DECIMAL(16,2) NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st_workflow_stmt FROM @st_workflow_sql;
EXECUTE st_workflow_stmt;
DEALLOCATE PREPARE st_workflow_stmt;

INSERT IGNORE INTO progress_reviews (progress_id, state, approved_qty, revision) SELECT dprogress_id, 'legacy', 0, 1 FROM daily_progress;

INSERT INTO module_permissions (mpermission_module, mpermission_name, mpermission_slug, mpermission_added_on, mpermission_added_by, mpermission_updated_on, mpermission_updated_by, mpermission_status) SELECT 19, 'Site reports', 'user.reports', NOW(), 1, NOW(), 1, 1 WHERE NOT EXISTS (SELECT 1 FROM module_permissions WHERE mpermission_slug = 'user.reports');
INSERT INTO role_permissions (rpermission_role, rpermission_permission, rpermission_added_on, rpermission_added_by, rpermission_updated_on, rpermission_updated_by, rpermission_status) SELECT 1, p.mpermission_id, NOW(), 1, NOW(), 1, 1 FROM module_permissions p WHERE p.mpermission_slug = 'user.reports' AND NOT EXISTS (SELECT 1 FROM role_permissions r WHERE r.rpermission_role = 1 AND r.rpermission_permission = p.mpermission_id);

INSERT INTO module_permissions (mpermission_module, mpermission_name, mpermission_slug, mpermission_added_on, mpermission_added_by, mpermission_updated_on, mpermission_updated_by, mpermission_status) SELECT 19, 'Export site reports', 'user.reports_export', NOW(), 1, NOW(), 1, 1 WHERE NOT EXISTS (SELECT 1 FROM module_permissions WHERE mpermission_slug = 'user.reports_export');
INSERT INTO role_permissions (rpermission_role, rpermission_permission, rpermission_added_on, rpermission_added_by, rpermission_updated_on, rpermission_updated_by, rpermission_status) SELECT 1, p.mpermission_id, NOW(), 1, NOW(), 1, 1 FROM module_permissions p WHERE p.mpermission_slug = 'user.reports_export' AND NOT EXISTS (SELECT 1 FROM role_permissions r WHERE r.rpermission_role = 1 AND r.rpermission_permission = p.mpermission_id);

INSERT INTO module_permissions (mpermission_module, mpermission_name, mpermission_slug, mpermission_added_on, mpermission_added_by, mpermission_updated_on, mpermission_updated_by, mpermission_status) SELECT 4, 'Approve reported quantities', 'user.review_progress', NOW(), 1, NOW(), 1, 1 WHERE NOT EXISTS (SELECT 1 FROM module_permissions WHERE mpermission_slug = 'user.review_progress');
INSERT INTO role_permissions (rpermission_role, rpermission_permission, rpermission_added_on, rpermission_added_by, rpermission_updated_on, rpermission_updated_by, rpermission_status) SELECT 1, p.mpermission_id, NOW(), 1, NOW(), 1, 1 FROM module_permissions p WHERE p.mpermission_slug = 'user.review_progress' AND NOT EXISTS (SELECT 1 FROM role_permissions r WHERE r.rpermission_role = 1 AND r.rpermission_permission = p.mpermission_id);

INSERT INTO module_permissions (mpermission_module, mpermission_name, mpermission_slug, mpermission_added_on, mpermission_added_by, mpermission_updated_on, mpermission_updated_by, mpermission_status) SELECT 24, 'Record site consumption', 'user.site_material_usage', NOW(), 1, NOW(), 1, 1 WHERE NOT EXISTS (SELECT 1 FROM module_permissions WHERE mpermission_slug = 'user.site_material_usage');
INSERT INTO role_permissions (rpermission_role, rpermission_permission, rpermission_added_on, rpermission_added_by, rpermission_updated_on, rpermission_updated_by, rpermission_status) SELECT 1, p.mpermission_id, NOW(), 1, NOW(), 1, 1 FROM module_permissions p WHERE p.mpermission_slug = 'user.site_material_usage' AND NOT EXISTS (SELECT 1 FROM role_permissions r WHERE r.rpermission_role = 1 AND r.rpermission_permission = p.mpermission_id);

INSERT INTO module_permissions (mpermission_module, mpermission_name, mpermission_slug, mpermission_added_on, mpermission_added_by, mpermission_updated_on, mpermission_updated_by, mpermission_status) SELECT 10, 'View labour payment review', 'user.labour_payments', NOW(), 1, NOW(), 1, 1 WHERE NOT EXISTS (SELECT 1 FROM module_permissions WHERE mpermission_slug = 'user.labour_payments');
INSERT INTO role_permissions (rpermission_role, rpermission_permission, rpermission_added_on, rpermission_added_by, rpermission_updated_on, rpermission_updated_by, rpermission_status) SELECT 1, p.mpermission_id, NOW(), 1, NOW(), 1, 1 FROM module_permissions p WHERE p.mpermission_slug = 'user.labour_payments' AND NOT EXISTS (SELECT 1 FROM role_permissions r WHERE r.rpermission_role = 1 AND r.rpermission_permission = p.mpermission_id);

INSERT INTO module_permissions (mpermission_module, mpermission_name, mpermission_slug, mpermission_added_on, mpermission_added_by, mpermission_updated_on, mpermission_updated_by, mpermission_status) SELECT 10, 'Record labour payments and advances', 'user.record_labour_payment', NOW(), 1, NOW(), 1, 1 WHERE NOT EXISTS (SELECT 1 FROM module_permissions WHERE mpermission_slug = 'user.record_labour_payment');
INSERT INTO role_permissions (rpermission_role, rpermission_permission, rpermission_added_on, rpermission_added_by, rpermission_updated_on, rpermission_updated_by, rpermission_status) SELECT 1, p.mpermission_id, NOW(), 1, NOW(), 1, 1 FROM module_permissions p WHERE p.mpermission_slug = 'user.record_labour_payment' AND NOT EXISTS (SELECT 1 FROM role_permissions r WHERE r.rpermission_role = 1 AND r.rpermission_permission = p.mpermission_id);

INSERT IGNORE INTO site_workflow_installations (version, installed_at) VALUES (1, NOW());
SELECT 'SiteTrack workflows database update complete' AS result;
