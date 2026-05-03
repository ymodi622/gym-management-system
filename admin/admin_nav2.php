<style>
    .admin-minimal-nav {
        height: 80px;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        display: flex;
        justify-content: center;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .admin-minimal-nav .logo {
        height: 45px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-minimal-nav .logo img {
        height: 100%;
        width: auto;
    }

    .admin-minimal-nav .logo span {
        color: #fff;
        font-weight: 800;
        font-size: 1.25rem;
        letter-spacing: -0.025em;
        text-transform: uppercase;
    }
</style>

<nav class="admin-minimal-nav">
    <div class="logo">
        <img src="logo2.png" alt="Willty Admin">
        <span>Willty Admin</span>
    </div>
</nav>