# Laravel Notes App — Teaching Curriculum
### From Blade Fullstack → REST API → (future) Livewire

This assumes viewers already have: PHP, Composer, MySQL, Navicat Lite installed, and know basic Laravel routing (`routes/web.php`, closures, route parameters).

Teaching pattern to keep consistent: **show the docs page → explain the concept in plain English → type it live → break it on purpose → fix it → recap what to search next time.**

---

## PART 0 — Bridge Video (5–10 min): "From Routes to Real Features"

Purpose: connect what they know (routing) to what's coming (MVC).

Concepts to introduce, in this order:
1. **MVC in Laravel** — where Models, Views, Controllers live in the folder structure (`app/Models`, `resources/views`, `app/Http/Controllers`). Just a mental map, not deep theory yet.
2. **Artisan CLI** — `php artisan list`, `php artisan --version`, `php artisan serve`. Show docs: [laravel.com/docs/artisan](https://laravel.com/docs/artisan)
3. **.env and database config** — connect Laravel to MySQL, test connection with `php artisan migrate` (even before making a table, just to prove the DB connects and creates the default `migrations` table). Show it in Navicat Lite so they *see* the table appear — ties back to their existing tool knowledge.

This video ends with: "Now we're ready to build something real — a Notes app."

---

## PART 1 — Fullstack Notes App with Blade

### Step 1: Plan the feature (2–3 min, no code)
- Define what a "Note" is: `id`, `title`, `body`, `created_at`, `updated_at`
- List the 5 actions: view all notes, view one note, create a note, edit a note, delete a note
- Say out loud: "This maps to something Laravel already has a name for — CRUD, and a routing convention called *resource routing*." (plants the seed before showing it)

### Step 2: Migration — creating the notes table
- Docs: [laravel.com/docs/migrations](https://laravel.com/docs/migrations)
- Command: `php artisan make:migration create_notes_table`
- Concepts to explain: what a migration is (version control for your DB schema), `Schema::create`, column types (`string`, `text`, `timestamps`)
- Run `php artisan migrate`, then show the table appear in Navicat Lite (reinforces earlier lesson)

### Step 3: Model — Eloquent basics
- Docs: [laravel.com/docs/eloquent](https://laravel.com/docs/eloquent)
- Command: `php artisan make:model Note`
- Concepts: what an ORM is, convention over configuration (Note model ↔ `notes` table automatically), `$fillable` and mass assignment protection (important — beginners get the "MassAssignmentException" error constantly, pre-empt it here)

### Step 4: Controller — resource controller
- Docs: [laravel.com/docs/controllers#resource-controllers](https://laravel.com/docs/controllers#resource-controllers)
- Command: `php artisan make:controller NoteController --resource --model=Note`
- Concepts: explain the 7 RESTful methods it generates (`index, create, store, show, edit, update, destroy`) and what each is *for* — this is the single most important concept for the rest of the series, spend real time here. Draw or show a table:

| Method | HTTP Verb | Purpose |
|---|---|---|
| index | GET | list all |
| create | GET | show the "new note" form |
| store | POST | save a new note |
| show | GET | view one note |
| edit | GET | show the "edit" form |
| update | PUT/PATCH | save changes |
| destroy | DELETE | delete a note |


| Method | HTTP Verb | Purpose |
|---|---|---|
| Tutorial | Method | HTTP Verb |

### Step 5: Routes — resource routing
- Docs: [laravel.com/docs/controllers#resource-controllers](https://laravel.com/docs/controllers#resource-controllers) (routing part) + `php artisan route:list`
- Show `Route::resource('notes', NoteController::class);` and immediately run `php artisan route:list` — this visually proves the 7 routes exist and connects directly back to their prior routing lesson. Strong "aha" moment.

### Step 6: Blade basics + layout
- Docs: [laravel.com/docs/blade](https://laravel.com/docs/blade)
- Concepts, in order:
  - Blade file naming (`index.blade.php`)
  - `@extends`, `@section`, `@yield` — build one master `layout.blade.php` first
  - `{{ }}` for escaped output vs `{!! !!}` (mention XSS briefly, why escaping matters)
  - `@foreach` to loop over notes in `index.blade.php`
  - `@if` / `@empty` for "no notes yet" state

### Step 7: Create + Store flow
- Build `create.blade.php` with a form
- Concepts: `@csrf` directive (explain CSRF protection simply — "Laravel blocks fake form submissions, this token proves it's really your form"), form `method="POST"` + `action="{{ route('notes.store') }}"`, named routes with `route()`
- In controller: `$request->validate([...])`, docs: [laravel.com/docs/validation](https://laravel.com/docs/validation)
- `Note::create($request->all())` or validated data
- Redirect: `return redirect()->route('notes.index')->with('success', 'Note created!')`
- Show flash messages in the layout: `@if(session('success'))`

### Step 8: Show + Edit + Update
- `show.blade.php` — display one note (`route('notes.show', $note)`) — explain route model binding here (docs: [laravel.com/docs/routing#route-model-binding](https://laravel.com/docs/routing#route-model-binding)) since it quietly powers `{{ $note->title }}` working without manual `find()`
- `edit.blade.php` — same form as create, pre-filled, but note the **method spoofing** trick: `@method('PUT')` inside the form since HTML forms don't support PUT natively — important "why" moment
- `update()` in controller

### Step 9: Delete
- Delete button as a mini-form with `@method('DELETE')` + `@csrf`
- `destroy()` — `$note->delete()`, redirect back with a message

### Step 10: Polish pass
- Extract repeated form fields into a Blade **component** or **@include partial** (docs: [laravel.com/docs/blade#components](https://laravel.com/docs/blade#components)) — light intro, not deep dive
- Recap the full request lifecycle on screen: Route → Controller → Model → View → Response

**End of Part 1: viewers have a working fullstack Notes app.**

---

## PART 2 — Same App, as a REST API

Framing for viewers: "Same database, same Model — we're just changing how the *outside world* talks to it."

### Step 1: Why an API is different
- No Blade views — Laravel returns JSON instead of HTML
- No browser forms — client apps (JS frontend, mobile app, Postman) send requests directly

### Step 2: routes/api.php
- Docs: [laravel.com/docs/routing#api-routes](https://laravel.com/docs/routing#api-routes)
- Explain `api.php` vs `web.php` — no CSRF needed here, routes prefixed with `/api` automatically, stateless by default
- `Route::apiResource('notes', NoteController::class)` — point out it's only 5 routes now (no `create`/`edit` — no forms needed for an API), run `route:list` again to compare

### Step 3: New controller (or reuse with adjustments)
- Recommend a fresh controller: `php artisan make:controller Api/NoteController --api --model=Note`
- Concept: `--api` flag skips `create`/`edit` stub methods automatically — ties back to Step 2 realization

### Step 4: Returning JSON
- Show the simplest version first: `return Note::all();` — Laravel auto-converts to JSON, explain why (Eloquent collections implement `Arrayable`/`Jsonable`)
- Then introduce **API Resources** for control over the shape of the response:
  - Docs: [laravel.com/docs/eloquent-resources](https://laravel.com/docs/eloquent-resources)
  - `php artisan make:resource NoteResource`
  - Show transforming output, hiding fields, adding computed fields

### Step 5: Store/Update via API
- Same validation concept, but now return JSON responses with correct status codes instead of redirects
- Docs: [laravel.com/docs/responses#json-responses](https://laravel.com/docs/responses#json-responses)
- Teach HTTP status codes explicitly — this is a huge concept for API work:

| Situation | Status Code |
|---|---|
| Success (GET) | 200 |
| Created | 201 |
| No content (delete success) | 204 |
| Validation error | 422 |
| Not found | 404 |
| Unauthorized | 401 |

- `return response()->json($note, 201);`

### Step 6: Testing the API
- Introduce Postman or Insomnia (or `curl` for the terminal-comfortable crowd) — this is often the first time beginners test something without a browser UI, spend time here
- Walk through all 5 endpoints live

### Step 7: FormRequest for cleaner validation (optional but recommended)
- Docs: [laravel.com/docs/validation#form-request-validation](https://laravel.com/docs/validation#form-request-validation)
- `php artisan make:request StoreNoteRequest` — shows a cleaner pattern than inline `$request->validate()`, good habit to build early

### Step 8: Mention (don't fully teach yet) authentication
- Briefly show that right now the API is wide open, and name-drop **Laravel Sanctum** as "the next logical step" for a future video — sets up a natural sequel without derailing this one. Docs: [laravel.com/docs/sanctum](https://laravel.com/docs/sanctum)

**End of Part 2: viewers have the same Notes app exposed as a working, testable REST API.**

---

## PART 3 — Where Livewire Fits Later

When you get there, frame it against what they already know:
- "Remember how in Part 1 you submitted a form and the whole page reloaded? Livewire lets you skip that — the page updates without a full reload, but you still write it in PHP, no separate JS framework needed."
- Good next mini-project: rebuild the same Notes app's create/edit/delete as a Livewire component, so the comparison is 1:1 with what they already built in Blade.

---

## Suggested Video Breakdown (if you want to split it up)

1. Bridge video: MVC + Artisan + DB connection check
2. Migrations + Model + Navicat verification
3. Resource Controller + Resource Routes (the "aha, 7 routes" video)
4. Blade layout + index + show pages
5. Create/store + validation + flash messages
6. Edit/update + delete (method spoofing explained)
7. Converting to an API: routes, controller, JSON responses
8. API Resources + status codes
9. Testing with Postman + wrap-up, tease Sanctum auth

## Recurring Teaching Device
Every video, briefly return to this phrase for viewers: *"You don't need to memorize this syntax — you need to remember it exists, and know the two or three words to search in the Laravel docs to find it again."* Since your whole channel promise is built on that, it might be worth ending each video with a 10-second "here's what to Google if you forget" recap of that video's 2–3 key terms.
