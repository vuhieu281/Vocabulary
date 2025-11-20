<?php
// Shared admin styles
?>
.admin-container {
    display: flex;
    min-height: 100vh;
    background-color: #f5f7fa;
}

.admin-sidebar {
    width: 250px;
    background-color: #2c3e50;
    color: white;
    padding: 20px;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
}

.sidebar-header h2 {
    margin: 0 0 30px 0;
    font-size: 24px;
    border-bottom: 2px solid #34495e;
    padding-bottom: 15px;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    margin-bottom: 10px;
}

.sidebar-menu .nav-link {
    display: block;
    color: #ecf0f1;
    text-decoration: none;
    padding: 12px 15px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.sidebar-menu .nav-link:hover,
.sidebar-menu .nav-link.active {
    background-color: #3498db;
    color: white;
}

.sidebar-menu .logout {
    margin-top: 30px;
    background-color: #e74c3c;
}

.admin-main {
    margin-left: 250px;
    flex: 1;
    padding: 30px;
    overflow-y: auto;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.admin-header h1 {
    margin: 0;
    font-size: 28px;
    color: #2c3e50;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
}

.table thead {
    background-color: #ecf0f1;
}

.table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #2c3e50;
}

.table td {
    padding: 12px;
    border-bottom: 1px solid #ecf0f1;
}

.table tr:hover {
    background-color: #f9f9f9;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-secondary {
    background-color: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background-color: #7f8c8d;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
    font-size: 14px;
    font-family: inherit;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
}

@media (max-width: 768px) {
    .admin-container {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .admin-main {
        margin-left: 0;
    }
}
