<?PHP
    session_start();
    if (isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username == "galleryadmin" && $password == "securepw") {
            $_SESSION['05e252a0-8c03-4b70-95fc-5f64a6a2df6c'] = session_id();
            header("Location:../index.php");
        }
        else {
            echo "<br><p class=\"text-danger\">Incorrect login credentials</p>";
        }
    }

    include("../includes/header.php");
    

    echo "<h2 class=\"text-dark\">Login</h2>";
echo "<form action=\"";
echo htmlspecialchars($_SERVER['PHP_SELF']);
echo "\" method=\"post\" name=\"login\">
    <label for=\"login\" class=\"mt-3\">Username</label>
    <input type=\"text\" id=\"username\" name=\"username\" class=\"form-control\">
    <label for=\"password\" class=\"mt-3\">Password</label>
    <input type=\"password\" id=\"password\" name=\"password\" class=\"form-control\">
    <input type=\"submit\" value=\"Log In\" name=\"submit\" class=\"btn btn-primary mt-3\">
</form>";

include("../includes/footer.php");
