
<header class="admin-header">
    <div class="header-content">
        <h1>TechBook Admin</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="reviews.php">Reviews</a>
            <a href="stores.php">Stores</a>
            <a href="comments.php">Comments</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<style>
.admin-header {
    background: var(--primary-color);
    color: white;
    padding: 1rem 2rem;
}

.header-content {
    max-width: 1200px;
    margin: 0 auto;
}

.admin-header h1 {
    margin: 0;
    font-size: 1.5rem;
}

.admin-header nav {
    margin-top: 1rem;
}

.admin-header nav a {
    color: white;
    text-decoration: none;
    margin-right: 1.5rem;
    padding: 0.5rem 0;
}

.admin-header nav a:hover {
    color: var(--accent-color);
}
</style>