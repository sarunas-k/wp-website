<?php
/*  
 * Layout block: Posts grid
 * 
 * Rendering to DOM:
 * get_my_block('post-grid', $arguments)
 * 
 * @arguments Array
 * post_type:   required
 * limit:       optional (number of posts to show 1-N)
 * start:       optional (starting post number in query 1-N)
 * image_size:  optional (1-4) default: 4
 * columns:     optional (grid layout columns 1-3) default: 1
 * margin:      optional (1-5) default: 3
 * 
 */
if (!array_key_exists('post_type', $args))
    return;

$query_args = array(
    'post_type' => $args['post_type'],
    'orderby' => 'date',
    'order' => 'DESC'
);


if (array_key_exists('limit', $args))
    $query_args['posts_per_page'] = $args['limit'];

if (array_key_exists('start', $args))
    $query_args['offset'] = $args['start'] - 1;

$query = new WP_Query($query_args);

if (!$query->have_posts())
    return;

$image_size = 4;
if (array_key_exists('image_size', $args) && is_numeric($args['image_size']))
    $image_size = $args['image_size'];

// COLUMNS
$columnClass = 'col-sm-12';
if (array_key_exists('columns', $args)) {
    if ($args['columns'] == 2) {
        $columnClass = 'col-sm-6';
    } elseif ($args['columns'] == 3) {
        $columnClass = 'col-sm-4';
    }
}

// MARGIN BETWEEN POST GRID 1 - 5
$margin = 3;
if (array_key_exists('margin', $args) && is_numeric($args['margin'])) {
    $margin = $args['margin'];
}

?>
<div class="custom-post-grid container-fluid <?php if (array_key_exists('class', $args)) { echo $args['class']; } ?>">
    <div class="row<?php echo ' g-' . $margin; ?>">
        <?php

        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="item-wrapper <?php echo $columnClass; ?>">
                <div class="custom-post-grid-item <?php if (array_key_exists('shadow', $args)) { echo 'box-shadow'; } ?>">
                    <div class="custom-post-grid-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('crop-' . $image_size, array('alt' => get_the_title(), 'itemprop' => 'image', )); ?>
                        </a>
                    </div>
                    <div class="custom-post-grid-content">

                        <h3>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <?php if (array_key_exists('excerpt', $args) && $args['excerpt'] == true) {
                                 the_excerpt(); 
                         } 
                         if (array_key_exists('date', $args) && $args['date'] == true) { ?>
                            <div class="custom-post-grid-footer">
                                    <p><?php echo get_the_date(); ?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php 
        }
        ?>
    </div>
</div>