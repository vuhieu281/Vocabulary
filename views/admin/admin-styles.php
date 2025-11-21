<?php
// Shared admin styles
?>
.admin-container {
    display: flex;
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
}

.admin-sidebar {
    width: 280px;
    background: linear-gradient(180deg, #1e3a5f 0%, #2c4a7e 100%);
    color: white;
    padding: 0;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
    z-index: 100;
}

.sidebar-header {
    padding: 30px 25px 25px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.2);
}

.sidebar-header h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.3px;
}

.sidebar-header h2 i {
    font-size: 24px;
    color: #4fc3f7;
}

.sidebar-menu {
    list-style: none;
    padding: 20px 12px;
    margin: 0;
}

.sidebar-menu li {
    margin-bottom: 8px;
}

.sidebar-menu .nav-link {
    display: flex;
    align-items: center;
    gap: 15px;
    color: #b8c9e0;
    text-decoration: none;
    padding: 14px 18px;
    border-radius: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 500;
    font-size: 14.5px;
    position: relative;
    overflow: hidden;
}

.sidebar-menu .nav-link i {
    font-size: 18px;
    min-width: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.sidebar-menu .nav-link:hover {
    background-color: rgba(79, 195, 247, 0.15);
    color: #4fc3f7;
    padding-left: 22px;
}

.sidebar-menu .nav-link:hover i {
    transform: translateX(3px);
}

.sidebar-menu .nav-link.active {
    background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    color: white;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(79, 195, 247, 0.35);
}

.sidebar-menu .nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: rgba(255, 255, 255, 0.3);
}

.sidebar-menu .nav-link.active i {
    color: white;
}

.sidebar-menu .logout {
    margin-top: 20px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%);
    color: white !important;
}

.sidebar-menu .logout:hover {
    background: linear-gradient(135deg, #ff5252 0%, #ff3838 100%) !important;
    color: white !important;
}

.sidebar-menu .logout i {
    color: white;
}

.admin-main {
    margin-left: 280px;
    flex: 1;
    padding: 40px;
    overflow-y: auto;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background-color: white;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.admin-header h1 {
    margin: 0;
    font-size: 28px;
    color: #1e3a5f;
    font-weight: 700;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.table thead {
    background: linear-gradient(135deg, #1e3a5f 0%, #2c4a7e 100%);
}

.table th {
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    color: white;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 14px 20px;
    border-bottom: 1px solid #f0f2f5;
    font-size: 14px;
}

.table tr:hover {
    background-color: #f8f9fb;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #29b6f6 0%, #0288d1 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(41, 182, 246, 0.3);
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
    color: #1e3a5f;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid #e0e6ed;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s ease;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #4fc3f7;
    box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.1);
    background-color: #f8fbff;
}

@media (max-width: 768px) {
    .admin-container {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
        height: auto;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .admin-main {
        margin-left: 0;
        padding: 20px;
    }
}