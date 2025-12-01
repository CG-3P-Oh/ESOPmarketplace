<?php

// functions.php (child theme)

/**
 * Add accessible aria-labels + safe rel to Divi Social Media Follow links.
 * Works for et_pb_social_media_follow and the per-network child "module".
 */
add_filter( 'et_module_shortcode_output', function( $output, $render_slug, $module_instance ) {
    // Only touch Social Media Follow modules
    if ( $render_slug !== 'et_pb_social_media_follow' && $render_slug !== 'et_pb_social_media_follow_network' ) {
        return $output;
    }

    if ( trim( $output ) === '' ) {
        return $output;
    }

    // Try to parse the snippet HTML safely
    if ( ! class_exists( 'DOMDocument' ) ) {
        // If DOM extension is unavailable, bail safely.
        return $output;
    }

    libxml_use_internal_errors( true );
    $doc = new DOMDocument();

    // Ensure UTF-8 is handled correctly
    $html = '<?xml encoding="utf-8" ?>' . $output;
    $doc->loadHTML( $html );

    $links = $doc->getElementsByTagName( 'a' );

    // Map class→network name for clear labels
    $network_map = [
        'et-social-facebook'  => 'Facebook',
        'et-social-instagram' => 'Instagram',
        'et-social-twitter'   => 'Twitter',
        'et-social-x'         => 'X',
        'et-social-linkedin'  => 'LinkedIn',
        'et-social-youtube'   => 'YouTube',
        'et-social-pinterest' => 'Pinterest',
        'et-social-tiktok'    => 'TikTok',
        'et-social-rss'       => 'RSS',
    ];

    foreach ( $links as $a ) {
        $class_attr = ' ' . trim( $a->getAttribute( 'class' ) ) . ' ';
        $network = 'social';

        foreach ( $network_map as $needle => $label ) {
            if ( strpos( $class_attr, ' ' . $needle . ' ' ) !== false ) {
                $network = $label;
                break;
            }
        }

        // Only label links that open in a new tab OR have no explicit target (Divi usually sets _blank)
        $target = strtolower( $a->getAttribute( 'target' ) );
        if ( $target === '_blank' || $target === '' ) {
            if ( ! $a->hasAttribute( 'aria-label' ) ) {
                $a->setAttribute( 'aria-label', "Visit us on {$network} (opens in a new tab)" );
            }
            // Ensure rel safety for target=_blank
            $rel = trim( $a->getAttribute( 'rel' ) );
            $rels = preg_split( '/\s+/', $rel ) ?: [];
            $rels = array_unique( array_filter( array_merge( $rels, [ 'noopener', 'noreferrer' ] ) ) );
            $a->setAttribute( 'rel', implode( ' ', $rels ) );
        }
    }

    // Extract the body innerHTML (DOMDocument wraps with <html><body>…)
    $final = $doc->saveHTML();
    if ( preg_match( '~<body>(.*)</body>~is', $final, $m ) ) {
        return $m[1];
    }

    return $output;
}, 10, 3 );
