<style>
    .navbar {
        height: 10vh;
        background-color: black;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        padding: 0 30px;
    }

    .logo {
        height: 8vh;
        display: flex;
        flex-direction: row;
        align-items: center;
        z-index: 2;
        cursor: pointer;
    }

    .logoImg img {
        height: 6vh;
        width: auto;
    }

    .logo h3 {
        color: #fff;
        margin-left: 15px;
        letter-spacing: 1px;
        font-size: 1.3rem;
        font-weight: 700;
    }
</style>

<div class="navbar">
    <div class="logo" onclick="homeCaller()">
        <div class="logoImg">
            <img src="admin/logo2.png" alt="logo">
        </div>
        <h3>Willity Fitness</h3>
    </div>
</div>

<script>
    function homeCaller() {
        window.location.href = 'home.php';
    }
</script>