<section class="wplc_height_calculator">
    <h2><?php _e( 'Berekening naar hoogte of breedte object', 'lens-calculator'); ?></h2>
    <p><?php _e('Het berekenen van het juiste brandpunt van de lens ten opzichte van de hoogte of breedte en de afstand van het bewaakte gebied.', 'lens-calculator'); ?></p>
    <?php do_action( 'wplc_before_height_width_form' ); ?>
    <form id="wpcl-form" name="hoogte" autocomplete="off">
        <div id="hoogte-answer-container">
            <label for="hoogte-answer"><?php _e( 'Stap 1: Beeldsensorgrootte:', 'lens-calculator' ); ?></label>
            <div>
                <div class="custom-select-wrapper"><?php echo wplc_sensor_select('answer1', 'hoogte-answer'); ?></div>
            </div>
        </div>
        <div id="objectafstand-container">
            <label for="hoogte-objectafstand"><?php _e( 'Stap 2: Scèneafstand (m):', 'lens-calculator' ); ?></label>
            <div>
                <input type="number" inputmode="numeric" name="objectafstand" id="hoogte-objectafstand"
                    class="wplc_field" min="0" max="999" minlength="1" maxlength="3" step="1" pattern="\d*" placeholder="0" />
                <p class="small"><?php _e( 'Alleen hele meters gebruiken', 'lens-calculator' ); ?></p>
            </div>
        </div>
        <div id="objecthoogte-container">
            <label for="objecthoogte"><?php _e( 'Stap 3: Scènehoogte (m):', 'lens-calculator' ); ?></label>
            <div>
                <input type="number" inputmode="numeric" name="objecthoogte" id="objecthoogte" class="wplc_field"
                    min="0" max="999" step="1" pattern="\d*" placeholder="0" />
                <p class="small"></p>
            </div>
        </div>
        <div id="objectbreedte-container">
            <label for="objectbreedte"><?php _e( 'Stap 4: Scènebreedte (m):', 'lens-calculator' ); ?></label>
            <div>
                <input type="number" inputmode="numeric" name="objectbreedte" id="objectbreedte" class="wplc_field"
                    min="0" max="999" step="1" pattern="\d*" placeholder="0" />
                <p class="small"></p>
            </div>
        </div>
        <div id="focalelengte-container">
            <label for="focalelengste"><?php _e( 'Brandpuntsafstand van de lens (mm):', 'lens-calculator' ); ?></label>
            <div>
                <input type="number" inputmode="numeric" name="output" id="focalelengste" class="wplc_field" placeholder="0" readonly />
            </div>
        </div>
        <div id="btn-container">
            <div></div>
            <div class="btn-group">
                <input class="button" onclick="computeLensForm(this.form)" type="button"
                    value="<?php _e( 'Berekenen', 'lens-calculator' ); ?>"
                    name="<?php _e( 'Berekenen', 'lens-calculator' ); ?>">
                <input class="button" id="reset-height-calc" type="reset"
                    value="<?php _e( 'Nieuwe berekening', 'lens-calculator' ); ?>"
                    name="<?php _e( 'Nieuwe berekening', 'lens-calculator' ); ?>" />
            </div>
        </div>
    </form>
    <?php do_action( 'wplc_after_height_width_form' ); ?>
</section>