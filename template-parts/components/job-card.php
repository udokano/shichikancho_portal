<?php
/** 求人カード（町で働くページの一覧） */
$job_id     = get_the_ID();
$job_pickup = ! empty( $args['pickup'] );
$job_types  = sc_job_type_labels( $job_id );
$job_pos    = (string) get_field( 'job_position', $job_id );
$job_tags   = array_filter( array_map( 'trim', explode( ',', (string) get_field( 'job_tags', $job_id ) ) ) );
?>
<a class="p-working__job<?php echo $job_pickup ? ' p-working__job--pickup' : ''; ?>"
   href="#job-modal-<?php echo esc_attr( $job_id ); ?>"
   data-modal-target="#job-modal-<?php echo esc_attr( $job_id ); ?>"
   data-category="<?php echo esc_attr( $job_pos ); ?>"
   data-type="<?php echo esc_attr( implode( ',', $job_types ) ); ?>">
	<span class="p-working__job-type"><?php echo esc_html( implode( '・', $job_types ) ); ?></span>
	<?php if ( $job_pickup ) : ?>
		<span class="p-working__job-pickup">おすすめ</span>
	<?php endif; ?>
	<h3 class="p-working__job-title"><?php the_title(); ?></h3>
	<?php if ( get_field( 'job_company', $job_id ) ) : ?>
		<p class="p-working__job-company"><?php echo sc_field( 'job_company', $job_id ); ?></p>
	<?php endif; ?>
	<p class="p-working__job-desc"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( (string) get_field( 'job_description', $job_id ) ), 90, '…' ) ); ?></p>
	<div class="p-working__job-meta">
		<?php if ( get_field( 'job_salary', $job_id ) ) : ?>
			<span class="p-working__job-meta-item">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-yen"></use></svg>
				<?php echo sc_field( 'job_salary', $job_id ); ?>
			</span>
		<?php endif; ?>
		<?php if ( get_field( 'job_location', $job_id ) ) : ?>
			<span class="p-working__job-meta-item">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-map-pin"></use></svg>
				<?php echo sc_field( 'job_location', $job_id ); ?>
			</span>
		<?php endif; ?>
		<?php if ( $job_pos ) : ?>
			<span class="p-working__job-meta-item">
				<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>
				<?php echo esc_html( $job_pos ); ?>
			</span>
		<?php endif; ?>
	</div>
	<!-- /.p-working__job-meta -->
	<?php if ( $job_tags ) : ?>
		<ul class="p-working__job-tags" role="list">
			<?php foreach ( $job_tags as $job_tag ) : ?>
				<li class="p-working__job-tag"><?php echo esc_html( $job_tag ); ?></li>
			<?php endforeach; ?>
		</ul>
		<!-- /.p-working__job-tags -->
	<?php endif; ?>
</a>
<!-- /.p-working__job -->
