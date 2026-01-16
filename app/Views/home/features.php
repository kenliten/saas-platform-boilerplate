<style>
    .feature-icon {
        width: 4rem;
        height: 4rem;
        border-radius: .75rem;
    }

    .icon-square {
        width: 3rem;
        height: 3rem;
        border-radius: .75rem;
    }

    .text-shadow-1 { text-shadow: 0 .125rem .25rem rgba(0, 0, 0, .25); }
    .text-shadow-2 { text-shadow: 0 .25rem .5rem rgba(0, 0, 0, .25); }
    .text-shadow-3 { text-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .25); }

    .card-cover {
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
    }
    
    .feature-icon-small {
        width: 3rem;
        height: 3rem;
    }
</style>

<div class="container px-4 py-5">
    <h2 class="pb-2 border-bottom"><?= __('features_title') ?></h2>
    <div class="row row-cols-1 row-cols-md-2 align-items-md-center g-5 py-5">
        <div class="col d-flex flex-column align-items-start gap-2">
            <h2 class="fw-bold text-body-emphasis"><?= __('features_heading') ?></h2>
            <p class="text-body-secondary"><?= __('features_description') ?></p>
            <a href="#" class="btn btn-primary btn-lg"><?= __('primary_button') ?></a>
        </div>
        <div class="col">
            <div class="row row-cols-1 row-cols-sm-2 g-4">
                <div class="col d-flex flex-column gap-2">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                        <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#collection"></use></svg>
                    </div>
                    <h4 class="fw-semibold mb-0 text-body-emphasis"><?= __('featured_item_title') ?></h4>
                    <p class="text-body-secondary"><?= __('featured_item_desc') ?></p>
                </div>
                <div class="col d-flex flex-column gap-2">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                        <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#gear-fill"></use></svg>
                    </div>
                    <h4 class="fw-semibold mb-0 text-body-emphasis"><?= __('featured_item_title') ?></h4>
                    <p class="text-body-secondary"><?= __('featured_item_desc') ?></p>
                </div>
                <div class="col d-flex flex-column gap-2">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                        <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#speedometer"></use></svg>
                    </div>
                    <h4 class="fw-semibold mb-0 text-body-emphasis"><?= __('featured_item_title') ?></h4>
                    <p class="text-body-secondary"><?= __('featured_item_desc') ?></p>
                </div>
                <div class="col d-flex flex-column gap-2">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-4 rounded-3">
                        <svg class="bi" width="1em" height="1em" aria-hidden="true"><use xlink:href="#table"></use></svg>
                    </div>
                    <h4 class="fw-semibold mb-0 text-body-emphasis"><?= __('featured_item_title') ?></h4>
                    <p class="text-body-secondary"><?= __('featured_item_desc') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>