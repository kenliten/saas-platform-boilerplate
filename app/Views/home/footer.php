<footer class="pt-4 pt-md-5 bg-dark" id="footer">
    <footer class="py-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-12 col-md-6">
                    <img class="mb-2" src="<?= env('APP_LOGO') ?>" alt="<?= env('APP_NAME') ?> Logo" width="48" />
                    <h5 class="mb-3 text-light d-flex align-items-center">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        <?= env('APP_NAME') ?>
                        <?= env('APP_VERSION') ?>
                    </h5>
                    <p class="small mb-4 text-light">
                        <?= __('footer_about') ?>
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-light mb-3">
                        <?= __('footer_product') ?>
                    </h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="#features">
                                <?= __('nav_features') ?>
                            </a></li>
                        <li><a href="/register">
                                <?= __('nav_pricing') ?>
                            </a></li>
                        <li><a href="/login">
                                <?= __('nav_login') ?>
                            </a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-light mb-3">
                        <?= __('footer_company') ?>
                    </h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="#">
                                <?= __('footer_about_us') ?>
                            </a></li>
                        <li><a href="#">
                                <?= __('footer_careers') ?>
                            </a></li>
                        <li><a href="#">
                                <?= __('footer_blog') ?>
                            </a></li>
                    </ul>
                </div>
            </div>
            <div class="border-top border-secondary mt-5 pt-4 text-center small">
                <p class="mb-0">&copy;
                    <?= date('Y') ?>
                    <?= env('APP_NAME') ?>.
                    <?= __('footer_rights') ?>
                </p>
            </div>
        </div>
    </footer>