<div class="row mt-5">
    <div class="col-11 col-md-11 col-lg-8 col-xl-5 ms-auto me-auto">
        <div class="card formular text-light" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

                <div class="mb-md-5 mt-md-4 pb-5">
                    <form action="" method="post">
                        <h2 class="fw-bold mb-2 text-uppercase">Registrace</h2>

                        <div class="form-outline form-white mb-4 d-flex flex-column">
                            <label class="me-auto">*jmeno:</label>
                            <input class="text-end" name="naem" type="text" placeholder="jmeno" required=""/>
                            <label class="me-auto">*prijmeni:</label>
                            <input class="text-end" name="surname" type="text" placeholder="přijmeni" required=""/>
                            <label class="me-auto">*login:</label>
                            <input class="text-end" name="username" type="text" placeholder="login" required=""/>
                            <label class="me-auto">*emai:</label>
                            <input class="text-end" name="email" type="email" placeholder="jmeno@gmail.com" required=""/>
                            <label class="text-start">*heslo:</label>
                            <input class="text-end" type="password" name="password" placeholder="******" required=""/>
                            <label class="text-start">*heslo znovu:</label>
                            <input class="text-end" type="passwordAgain" name="password" placeholder="******" required=""/>
                        </div>

                        <div class="form-outline form-white mb-2">
                            <input type="submit" name="submit" class="btn btn-primary" value="Zaregistrovat">
                        </div>

                </div>
                </form>
                <div>
                    <p class="mb-0">Už máš účet? <a href="login.php" class="text-white-50 fw-bold">Log In</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
<style>

    .formular {
        background-color: var(--color1);
    }
</style>