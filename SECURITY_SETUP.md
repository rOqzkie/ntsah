# API Key & Credential Security Setup

## What Was Done

Your project had **exposed credentials** in multiple files that were at risk of being committed to GitHub. I've implemented a secure environment variable system to protect them.

### Files Updated

**Core Setup Files:**
- ✅ Created `.env` file (contains actual credentials - NOT committed to Git)
- ✅ Created `.env.example` file (template for developers)
- ✅ Updated `.gitignore` to exclude `.env` and other sensitive files
- ✅ Created `classes/EnvLoader.php` (loads environment variables)
- ✅ Updated `initialize.php` to use environment variables

**Files with Hardcoded Credentials Secured:**
1. `llama_api.php` - LLaMA API key
2. `fetch_suggestions.php` - LLaMA API key (2 occurrences)
3. `inc/fetch_suggestions.php` - LLaMA API key  
4. `generate_gap.php` - Together API key
5. `get_similar_studies.php` - LLaMA API key
6. `extract_metadata.php` - Together API key
7. `forgot_password.php` - Email password
8. `forgot_password-adviser.php` - Email password
9. `verify.php` - Email password
10. `inc/verify.php` - Email password
11. `verify_otp.php` - Email password
12. `smtp_test.php` - Email password
13. `classes/Users.php` - Email password (3 occurrences)
14. `classes/Users(1).php` - Email password (3 occurrences)

### Exposed Credentials Found & Secured

- 🔐 **API Keys:** `faec3bc50f70bf402fed40f4c50b60f264eb0038abff458d25c9969a8e2dd89e`
- 🔐 **Database Password:** `kill_iGit09`
- 🔐 **Email Password:** `kill_iGit09`
- 🔐 **Database Username:** `u860258813_ntfsah`

---

## How It Works Now

### 1. Environment Variables System

The `EnvLoader.php` class:
- Reads the `.env` file on application startup
- Provides an `env()` helper function to access variables safely
- Falls back to default values if variables aren't set

### 2. Usage in Code

**Before (Insecure):**
```php
$apiKey = 'faec3bc50f70bf402fed40f4c50b60f264eb0038abff458d25c9969a8e2dd89e';
$mail->Password = 'kill_iGit09';
```

**After (Secure):**
```php
$apiKey = env('LLAMA_API_KEY');
$mail->Password = env('MAIL_PASSWORD', '');
```

### 3. .gitignore Protection

The `.gitignore` file prevents these files from being committed:
- `.env` - Contains actual credentials
- `.env.local` - Local overrides
- Other sensitive files

---

## Setup Instructions for Deployment

### For Local Development:

1. File `.env` already exists with your credentials
2. Application will automatically load variables from `.env`
3. Never commit `.env` to Git (it's in `.gitignore`)

### For Production/Team Members:

1. Copy `.env.example` to `.env`
   ```bash
   cp .env.example .env
   ```

2. Update `.env` with production credentials:
   ```env
   DB_SERVER=your_production_db_server
   DB_USERNAME=your_production_username
   DB_PASSWORD=your_production_password
   LLAMA_API_KEY=your_production_api_key
   MAIL_PASSWORD=your_production_email_password
   ```

3. Set proper file permissions (Linux/Mac):
   ```bash
   chmod 600 .env
   ```

4. Never share the `.env` file

---

## Important Security Notes

⚠️ **If you previously pushed these credentials to GitHub:**

1. **Revoke all exposed credentials immediately:**
   - Change database password
   - Rotate API keys
   - Change email password

2. **Remove sensitive history from Git:**
   ```bash
   # Install BFG Repo-Cleaner (recommended)
   brew install bfg  # macOS
   # Windows: Download from https://rtyley.github.io/bfg-repo-cleaner/
   
   # Clean history
   bfg --replace-text credentials.txt your-repo.git
   git reflog expire --expire=now --all
   git gc --prune=now --aggressive
   git push origin --force --all
   ```

3. **Or use git-filter-branch (slower):**
   ```bash
   git filter-branch --force --index-filter 'git rm --cached --ignore-unmatch path/to/file' --prune-empty --tag-name-filter cat -- --all
   git push origin --force --all
   ```

---

## Verification

To verify everything is working:

1. Check `.gitignore` includes `.env`:
   ```bash
   cat .gitignore | grep "\.env"
   ```

2. Verify `.env` is not tracked:
   ```bash
   git status
   ```
   (`.env` should NOT appear in the list)

3. Test that env variables load:
   ```php
   require './classes/EnvLoader.php';
   echo env('DB_USERNAME'); // Should output your database username
   ```

---

## Environment Variables Reference

| Variable | Purpose | Example |
|----------|---------|---------|
| `DB_SERVER` | Database host | `localhost` |
| `DB_USERNAME` | Database user | `u860258813_ntfsah` |
| `DB_PASSWORD` | Database password | `****` |
| `DB_NAME` | Database name | `u860258813_db_ntfsah` |
| `LLAMA_API_KEY` | LLaMA API key | `faec3bc5...` |
| `TOGETHER_API_KEY` | Together API key | `faec3bc5...` |
| `MAIL_HOST` | SMTP server | `smtp.hostinger.com` |
| `MAIL_USERNAME` | Email account | `ntsah.site@ntsah.site` |
| `MAIL_PASSWORD` | Email password | `****` |
| `MAIL_FROM` | Sender email | `ntsah.site@ntsah.site` |
| `MAIL_FROM_NAME` | Sender name | `NEMSU Archiving Hub` |
| `BASE_URL` | Application URL | `https://ntsah.site/` |

---

## Next Steps

1. ✅ Review `.env.example` and verify all variables are defined
2. ✅ Ensure `.env` is in `.gitignore`
3. ⚠️ **If pushed to GitHub:** Rotate and revoke all exposed credentials
4. ✅ Test application to ensure it works with environment variables
5. ✅ Deploy `.env.example` (NOT `.env`) to version control

---

## Support

If you have any issues:
- Check that `initialize.php` includes `EnvLoader.php` first
- Verify `.env` file exists and has correct values
- Ensure `classes/` directory is readable
- Check file permissions (`.env` should be readable by web server)
