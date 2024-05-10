<?php
    include_once './model/UserModel.php';
    include_once './security/Security.php'; 

    // Récupérer les informations de l'utilisateur connecté
    use Model\UserModel;
    use Security\Security;


    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    include_once './view/parts/header.php';

?>
<main>
    <section>
        <h1>Tableau de bord</h1>
        <!-- Afficher les informations de l'utilisateur connecté -->
        <?php            
            // Récupérer l'ID de l'utilisateur à partir de la session ou d'une autre source
            $userID = $_SESSION['user_id'];
            // Appeler la fonction getUserInfos pour obtenir les informations de l'utilisateur
        

            $userI = new UserModel();

            $userInfo = $userI-> getUserInfos($userID);

            
            if ($userInfo) {
                echo "Nom: " . $userInfo['nom'];
                echo "Prénom: " . $userInfo['prenom'];
                echo "Adresse: " . $userInfo['adresse'];
                echo "Email: " . $userInfo['email'];
            } else {
                echo "Impossible de récupérer les informations de l'utilisateur.";
            }
            ?>
        <!--Supprimer le compte -->
        <form action="index.php?action=close" method="post">
            <input type="hidden" name="csrf_token" value="<?= Security::sanitizeInput($_SESSION['csrf_token']); ?>">
            <button class="close-button" type="submit" name="delete_user">Supprimer mon compte</button>
        </form>
    </section>
</main>

<?php
include_once './view/parts/footer.php';
?>
