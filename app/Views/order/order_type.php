    <section id="page-title" class="page-title page-title-layout7">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h2 class="pagetitle__desc color-dark mb-2">Where to find us?</h2>
                </div>
            </div>
        </div>
    </section>


    <div class="container my-5 mx-auto">
        <div class="row mx-auto">
            <?php foreach ($restaurant as $r) : ?>
                <div class="col-sm-6 col-md-6 col-lg-6 pb-50 mx-auto">
                    <h2 class="pagetitle__desc color-dark"><?= $r['rest_name']; ?></h2>
                    <p><?= $r['rest_address']; ?><br><?= $r['rest_phone']; ?></p>
                    <a href="<?= $r['url']; ?>" class="navbar__action-btn-reserve btn btn__primary">Take Out</a>
                    <a href="#" class="navbar__action-btn-reserve btn btn__primary">Catering</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>