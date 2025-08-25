# Excel Upload → Select Sheet/Column → Preview → Save to DB (Laravel + Inertia + Vue 3)

1. **Upload** an Excel file (`.xls`, `.xlsx`)
2. **List sheets** and select one
3. **Read headers** and select a column
4. **Preview** the selected column values
5. **Persist** those values to the database

Built with **Laravel**, **Inertia.js**, **Vue 3 (TypeScript)**, and **PhpSpreadsheet**.

---

## 📦 Tech Stack

* Laravel 10/11
* Inertia.js + @inertiajs/vue3
* Vue 3 + TypeScript + Tailwind CSS
* PhpSpreadsheet (for Excel parsing)
* MySQL/PostgreSQL/SQLite (any supported DB)

---

## 🚀 Quick Start

### 1) Clone & Install

```bash
git clone https://github.com/officetoufiqur/php_spreadsheet.git
cd php_spreadsheet
cp .env.example .env
composer install
php artisan key:generate
npm install
```

Configure DB in `.env`, then run migrations:

```bash
php artisan migrate
```

### 2) Install PhpSpreadsheet

```bash
composer require phpoffice/phpspreadsheet
```

### 3) Dev servers

```bash
php artisan serve
# In a separate terminal
npm run dev
```

Open: `http://127.0.0.1:8000/excel`

---

## 🪪 CSRF & Security Notes

* Inertia/Laravel automatically handle CSRF for `POST` requests when using `useForm().post()`.
* Validate uploads if needed (max file size, MIME types).
* Consider moving uploaded files to a temporary disk and **delete after processing**.

---

## 🧰 Troubleshooting

### ❗️"The GET method is not supported for route excel/handle. Supported methods: POST."

* Cause: After saving, app redirects to a **GET** on `/excel/handle` (POST-only).
* Fix: In `save()`, redirect to a **GET** route, e.g. `return redirect()->route('excel.index')`.
* Ensure the Save button posts to `route('excel.save')` (not `excel.handle`).

### Blank columns / headers not found

* Ensure your first row contains header labels.
* If your header row is not row 1, adjust the logic accordingly.

