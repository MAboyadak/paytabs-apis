

<section class="vh-100">
    <div class="d-flex align-items-center h-100">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center h-100">

                <div class="col-lg-8">

                    <?php if(isset($_SESSION['error'])) { ?>
                        <div class="bg-danger text-white p-2 my-2">
                            <?php echo $_SESSION['error'] ; ?>
                        </div>
                    <?php unset($_SESSION['error']); } ?>

                    <div class="card text-white" style="border-radius: 15px; border-radius: 1rem; background-color:#306f5c">
                        <div class="card-body p-4">
                        <h2 class="text-uppercase text-center">Create an account</h2>

                        <form action="/inisev/register" method="post">

                            <div class="form-outline mb-2">
                                <label for="name" class="">Name 
                                    <?php if(isset($_SESSION['errors']) && isset($_SESSION['errors']['name'])) {?>
                                        <span class="bg-danger text-white px-2 mx-4 fw-bold my-3 rounded">
                                            <?php echo $_SESSION['errors']['name']; ?>
                                    </span>
                                    <?php } ?>
                                </label> 
                                <input type="text" name="name" class="form-control " />
                            </div>

                            <div class="form-outline mb-2">
                                <label for="email" class="">Email 
                                    <?php if(isset($_SESSION['errors']) && isset($_SESSION['errors']['email'])) {?>
                                        <span class="bg-danger text-white px-2 mx-4 fw-bold my-3 rounded">
                                            <?php echo $_SESSION['errors']['email']; ?>
                                    </span>
                                    <?php } ?>
                                </label> 
                                <input type="email" name="email" class="form-control " />
                            </div>

                            <div class="form-outline mb-2">
                                <label for="password" class="">Password 
                                    <?php if(isset($_SESSION['errors']) && isset($_SESSION['errors']['password'])) {?>
                                        <span class="bg-danger text-white px-2 mx-4 fw-bold my-3 rounded">
                                            <?php echo $_SESSION['errors']['password']; ?>
                                    </span>
                                    <?php } ?>
                                </label> 
                                <input type="password" name="password" class="form-control " />
                            </div>

                            <div class="form-outline mb-2">
                                <label for="confirm_password" class="">Confirm Password 
                                    <?php if(isset($_SESSION['errors']) && isset($_SESSION['errors']['confirm_password'])) {?>
                                        <span class="bg-danger text-white px-2 mx-4 fw-bold my-3 rounded">
                                            <?php echo $_SESSION['errors']['confirm_password']; ?>
                                    </span>
                                    <?php } ?>
                                </label> 
                                <input type="password" name="confirm_password" class="form-control " />
                            </div>

                            <div class="d-flex justify-content-center">
                            <button type="submit"
                                class="btn btn-success btn-block btn-lg gradient-custom-4 text-body mt-3">Register</button>
                            </div>

                        </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if(isset($_SESSION['errors'])) unset($_SESSION['errors']);?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>