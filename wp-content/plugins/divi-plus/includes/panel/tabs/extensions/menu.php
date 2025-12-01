<div id="el-settings-panel-extensions-wrap" class="el-settings-panel-group">
	<ul class="el-settings-panel-submenu">
		<li class="el-settings-panel-submenu-tab el-settings-panel-active-tab">
			<span data-href="#el-settings-panel-extensions-general-section">
				<?php esc_html_e( 'General', 'divi-plus' ); ?>
			</span>
		</li>
		<li class="el-settings-panel-submenu-tab">
			<span data-href="#el-settings-panel-extensions-scheduler-section">
				<?php esc_html_e( 'Scheduler', 'divi-plus' ); ?>
			</span>
		</li>
		<li class="el-settings-panel-submenu-tab el-settings-panel-tab">
			<span data-href="#el-settings-panel-extensions-visibility-manager-section">
				<?php esc_html_e( 'Visibility Manager', 'divi-plus' ); ?>
			</span>
		</li>
		<li class="el-settings-panel-submenu-tab el-settings-panel-tab">
			<span data-href="#el-settings-panel-extensions-particle-background-section">
				<?php esc_html_e( 'Particle Background', 'divi-plus' ); ?>
			</span>
		</li>
		<li class="el-settings-panel-submenu-tab">
			<span data-href="#el-settings-panel-extensions-unfold-section">
				<?php esc_html_e( 'Unfold', 'divi-plus' ); ?>
			</span>
		</li>
	</ul>
	<div class="el-settings-panel-sections-wrap">
		<table id="el-settings-panel-extensions-general-section" class="form-table el-settings-panel-section el-settings-panel-active-section">
		<?php
			settings_fields( 'el-settings-extensions-general-group' );
			do_settings_fields( esc_html( self::$menu_slug ), 'el-settings-extensions-general-section' );
		?>
		</table>
		<table id="el-settings-panel-extensions-scheduler-section" class="form-table el-settings-panel-section">
		<?php
			settings_fields( 'el-settings-extensions-scheduler-group' );
			do_settings_fields( esc_html( self::$menu_slug ), 'el-settings-extensions-scheduler-section' );
		?>
		</table>
		<table id="el-settings-panel-extensions-visibility-manager-section" class="form-table el-settings-panel-section">
		<?php
			settings_fields( 'el-settings-extensions-visibility-manager-group' );
			do_settings_fields( esc_html( self::$menu_slug ), 'el-settings-extensions-visibility-manager-section' );
		?>
		</table>
		<table id="el-settings-panel-extensions-particle-background-section" class="form-table el-settings-panel-section">
        <?php
            settings_fields( 'el-settings-extensions-particle-background-group' );
            do_settings_fields( esc_html( self::$menu_slug ), 'el-settings-extensions-particle-background-section' );
        ?>
        </table>
		<table id="el-settings-panel-extensions-unfold-section" class="form-table el-settings-panel-section">
		<?php
			settings_fields( 'el-settings-extensions-unfold-group' );
			do_settings_fields( esc_html( self::$menu_slug ), 'el-settings-extensions-unfold-section' );
		?>
		</table>
	</div>
</div>
