<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package unite
 */
?>
</div><!-- row -->
</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-info container">
        <div class="row">

            <div class="copyright">
                <?php echo of_get_option( 'custom_footer_text', 'unite' ); ?>

            </div>

        </div>
    </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>