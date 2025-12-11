<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Details - Jurnalku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1>Role Details: {{ $role->name }}</h1>
        <p>This is the show page for role ID: {{ $role->id }}</p>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back to Roles</a>
    </div>
</body>
</html>