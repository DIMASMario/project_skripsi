# 🗑️ DATABASE CLEANUP - EXECUTION GUIDE

## 📋 OVERVIEW

Dokumen ini berisi panduan lengkap untuk menjalankan database cleanup script yang akan menghapus tabel-tabel yang tidak digunakan dari database `db_desa_blanakan`.

### What Will Be Deleted:
- ✗ `security_logs` (0 rows - unused infrastructure)
- ✗ `template_surat_backup` (0 rows - duplicate backup)
- ✗ `settings` (0 rows - replaced by site_config)

### Space Freed:
- ~48KB total

### Safety:
- ✓ No foreign keys affected
- ✓ No active features depend on these tables
- ✓ All data already migrated or redundant

---

## 🚀 EXECUTION OPTIONS

### Option 1: Using SQL Script in phpMyAdmin (RECOMMENDED FOR BEGINNERS)

**Steps:**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select database: `db_desa_blanakan`
3. Go to "SQL" tab
4. Copy and paste SQL commands from: `CLEANUP_DATABASE_EXECUTE.sql`
5. Click "Go" button

**Expected Output:**
```
Query `DROP TABLE IF EXISTS `security_logs`` executed successfully
Query `DROP TABLE IF EXISTS `template_surat_backup`` executed successfully
Query `DROP TABLE IF EXISTS `settings`` executed successfully
```

---

### Option 2: Direct MySQL Command Line

**Steps:**
```bash
cd c:\wamp64\www\project-skripsi\desa_tanjungbaru

# Connect to MySQL
mysql -u root -p

# Select database
USE db_desa_blanakan;

# Execute cleanup
DROP TABLE IF EXISTS `security_logs`;
DROP TABLE IF EXISTS `template_surat_backup`;
DROP TABLE IF EXISTS `settings`;

# Verify
SELECT TABLE_NAME FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'db_desa_blanakan' 
ORDER BY TABLE_NAME;

# Exit
EXIT;
```

---

### Option 3: PHP Script (Using cleanup_database.php)

**Steps:**
```bash
cd c:\wamp64\www\project-skripsi\desa_tanjungbaru

# Run the cleanup script
php cleanup_database.php

# When prompted, type: YES (exactly as shown)
# Script will display confirmation and results
```

---

### Option 4: SQL Dump File

**Steps:**
```bash
cd c:\wamp64\www\project-skripsi\desa_tanjungbaru

# Execute SQL file directly
mysql -u root -p db_desa_blanakan < CLEANUP_DATABASE_EXECUTE.sql
```

---

## ✅ VERIFICATION

After running the cleanup, verify the tables were deleted:

### Option A: In phpMyAdmin
1. Refresh the database view
2. Look for these tables in the list:
   - `security_logs` - **Should NOT appear** ❌
   - `template_surat_backup` - **Should NOT appear** ❌
   - `settings` - **Should NOT appear** ❌
3. You should see `site_config` table instead ✓

### Option B: Using SQL Query
```sql
-- Run this query to count remaining tables
SELECT COUNT(*) as total_tables FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'db_desa_blanakan';

-- Result should be: 21 (was 24 before cleanup)
```

### Option C: List All Remaining Tables
```sql
SELECT TABLE_NAME, TABLE_ROWS 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'db_desa_blanakan' 
ORDER BY TABLE_NAME;
```

**Expected tables that should remain (21 total):**
- berita ✓
- desa ✓
- galeri ✓
- kabupaten_kota ✓
- kecamatan ✓
- kode_registrasi ✓
- kode_registrasi_backup ✓
- kontak ✓
- migrations ✓
- notifikasi ✓
- pengumuman ✓
- provinsi ✓
- registration_logs ✓
- site_config ✓
- **statistik_desa** ✓
- surat ✓
- surat_berkas ✓
- template_surat ✓
- user_roles ✓
- users ✓
- visitor_logs ✓

---

## ⚠️ BEFORE YOU START

### 1. BACKUP YOUR DATABASE (CRITICAL!)

**Method A: Using phpMyAdmin**
1. Open phpMyAdmin
2. Select `db_desa_blanakan`
3. Click "Export" button
4. Click "Go"
5. Save the file as `db_desa_blanakan_backup_BEFORE_CLEANUP.sql`

**Method B: Using Command Line**
```bash
mysqldump -u root -p db_desa_blanakan > c:\backup\db_desa_blanakan_backup_BEFORE_CLEANUP.sql
```

**Method C: Copy Database File**
```bash
# If using XAMPP, backup the database files
copy "C:\xampp\mysql\data\db_desa_blanakan" "C:\xampp\mysql\data\db_desa_blanakan_backup"
```

### 2. Stop All Running Services (Optional)
```bash
# Stop CodeIgniter server (if running)
# Stop any applications accessing the database
```

### 3. Document Current State
```bash
# Record current database statistics
-- Before cleanup:
-- Total tables: 24
-- Total rows: 1026+
```

---

## 🔄 ROLLBACK (If Something Goes Wrong)

If you need to restore the tables:

**Option 1: From Backup File**
```bash
mysql -u root -p db_desa_blanakan < db_desa_blanakan_backup_BEFORE_CLEANUP.sql
```

**Option 2: Recreate Empty Tables**
```sql
-- If you need to recreate the tables (without data):

CREATE TABLE `security_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `template_surat_backup` (
  `id` int UNSIGNED NOT NULL DEFAULT '0',
  `jenis_surat` varchar(100) DEFAULT NULL,
  `template` text,
  `updated_at` datetime DEFAULT NULL,
  KEY `jenis_surat` (`jenis_surat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key_name` varchar(100) NOT NULL,
  `value` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

---

## 📊 EXPECTED RESULTS

### Before Cleanup:
```
Database Statistics:
├── Tables: 24
├── Data rows: 1,026+
├── Size: ~2MB
└── Unused tables: 7 (empty/unused)
```

### After Cleanup:
```
Database Statistics:
├── Tables: 21 ✓ (reduced by 3)
├── Data rows: 1,026+ (same, no data deleted)
├── Size: ~1.95MB (freed ~48KB)
└── Unused tables: 4 (still empty but keeping for infrastructure)
```

---

## 🎯 NEXT STEPS (Phase 2/3 - Optional)

After Phase 1 cleanup, consider:

1. **Phase 2 (This Month):**
   - Consolidate other redundant tables if needed
   - Fix ENUM in surat table to include all letter types

2. **Phase 3 (Later):**
   - Delete more empty infrastructure tables:
     - `registration_logs` (0 rows)
     - `template_surat` (0 rows)
     - `surat_berkas` (0 rows)
     - `galeri` (0 rows)
     - `kontak` (0 rows) - only if form not used

3. **Maintenance:**
   - Regular database optimization: `OPTIMIZE TABLE surat;`
   - Monitor large tables like `visitor_logs`
   - Archive old data if needed

---

## 🆘 TROUBLESHOOTING

### Error: "Table doesn't exist"
**Cause:** Table already deleted
**Solution:** This is fine, the DROP TABLE IF EXISTS will just skip it

### Error: "Foreign key constraint"
**Cause:** Another table references this table
**Solution:** This shouldn't happen for these 3 tables. Check if safe to delete.

### Error: "Access Denied"
**Cause:** Insufficient MySQL user permissions
**Solution:** Use admin account or ask database administrator

### Error: "Cannot connect to MySQL"
**Cause:** MySQL server not running
**Solution:** Start XAMPP/WAMP MySQL service

---

## 📝 CHECKLIST

Before executing cleanup:
- [ ] I have backed up my database
- [ ] I have read this entire guide
- [ ] I understand which tables will be deleted
- [ ] I have verified no code depends on these tables
- [ ] I have stopped all running processes
- [ ] I have noted the current database statistics

Execute cleanup:
- [ ] I have selected the correct database: `db_desa_blanakan`
- [ ] I have copied the correct SQL commands
- [ ] I have executed the DROP TABLE commands
- [ ] I have received confirmation messages (Query executed successfully)
- [ ] I have verified the tables were deleted

Post-cleanup:
- [ ] I have verified remaining table count is 21
- [ ] I have confirmed database still works
- [ ] I have tested application features
- [ ] I have deleted cleanup scripts from production

---

## 📞 SUPPORT

If you encounter issues:

1. **Check the backup:** Restore from backup if needed
2. **Review logs:** Check `writable/logs/` for error messages
3. **Test application:** Verify all features still work
4. **Additional help:** Contact database administrator

---

**Last Updated:** March 17, 2026
**Status:** Ready for Production Cleanup
**Risk Level:** ⚠️ LOW (tables are empty, no data loss possible)
