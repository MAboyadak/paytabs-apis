<section>

    <h2 class="text-center my-4">Create New Invoice</h2>
    <div class="row justify-content-center">
   
        <div class="col-md-8">

            <?php
                if(isset($_SESSION['success'])) { 
            ?>
                <div class="bg-success p-2 my-2 text-white">
                    <?php echo $_SESSION['success'] ; } ?>
                </div>

            <?php
                if(isset($_SESSION['error'])) { 
            ?>
                <div class="bg-danger text-white p-2 my-2">
                    <?php echo $_SESSION['error'] ; ?>
                </div>
            <?php unset($_SESSION['error']); } ?>


            <form action="<?php echo (!isset($_SESSION['success']) ? '/paytabs-dev/invoices' : '/paytabs-dev/follow-refund' ) ; ?>" method="POST">
                
                <div class="row my-3">

                </div>
                
                <button type="submit" class="btn btn-primary my-2"><?php echo ( !isset($_SESSION['success']) ? 'Submit' : 'Follow-Up') ;?></button>

            </form>

        </div>

    </div>

</section>

<?php if(isset($_SESSION['success'])) unset($_SESSION['success']); ?>
<?php if(isset($_SESSION['errors'])) unset($_SESSION['errors']);?>