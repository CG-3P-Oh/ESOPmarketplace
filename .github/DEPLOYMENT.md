# Deployment Instructions

## Manual Deployment (Recommended - WORKS)

1. Open cPanel File Manager for new.esopmarketplace.com
2. Navigate to the directory you want to update
3. Upload changed files via drag-and-drop or Upload button
4. Overwrite when prompted

**Most common files to deploy:**
- `wp-content/mu-plugins/esop-advisor-system.php` - Advisor plugin
- `wp-content/themes/` - Theme files

## Why Manual?

Automated FTP deployments via GitHub Actions proved unreliable for this hosting setup. Manual deployment via cPanel is:
- ✅ Fast (30 seconds)
- ✅ Reliable (always works)
- ✅ Simple (no configuration needed)
- ✅ Visible (you see exactly what's uploaded)

## Alternative: FTP Client

If you prefer desktop software:
1. Use FileZilla or WinSCP
2. Connect with your cPanel FTP credentials
3. Upload changed files directly

**Server:** (your FTP server)
**Username:** (your FTP username)
**Path:** `/home/esopmark/new.esopmarketplace.com/`
