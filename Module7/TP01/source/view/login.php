<!-- login.php -->
<?php 
include_once 'parts/header.php'; 
use Security\Security;
?>
<main>
    <section id="log">
        <h1 id="un">Connexion</h1>
        <form action="index.php?action=login" method="post">
            <?php if (isset($_SESSION['csrf_token'])) : ?>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <?php else : ?>
                <?php Security::generate(); ?>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <?php endif; ?>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <?php
                if (isset($error) && $error === 'wrong_email_password') {
                    echo "<p class='error-message'>Email ou mot de passe incorrect.</p>";
                }
            ?>
            <button type="submit">Se connecter</button>
        </form>
    </section>
</main>

<?php include_once 'parts/footer.php'; ?>
