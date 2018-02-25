/* jshint node:true */
module.exports = function( grunt ) {
	'use strict';

	grunt.initConfig( {

		// Generate POT files.
		makepot: {
			options: {
				type: 'wp-plugin',
				domainPath: 'languages',
				potHeaders: {
					'report-msgid-bugs-to': 'support@somewherewarm.gr'
				}
			},
			go: {
				options: {
					potFilename: 'woocommerce-full-width-add-to-cart.pot',
					exclude: [
						'languages/.*',
						'assets/.*',
						'node-modules/.*',
						'woo-includes/.*'
					]
				}
			}
		},

		// Check textdomain errors.
		checktextdomain: {
			options:{
				text_domain: [ 'woocommerce-full-width-add-to-cart', 'woocommerce' ],
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			files: {
				src:  [
					'**/*.php', // Include all files
					'!apigen/**', // Exclude apigen/
					'!deploy/**', // Exclude deploy/
					'!node_modules/**' // Exclude node_modules/
				],
				expand: true
			}
		}
	});

	// Load NPM tasks to be used here.
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-checktextdomain' );

	// Register tasks.
	grunt.registerTask( 'dev', [
		'checktextdomain'
	]);

	grunt.registerTask( 'default', [
		'dev',
		'makepot'
	]);

	grunt.registerTask( 'domain', [
		'checktextdomain'
	]);
};
