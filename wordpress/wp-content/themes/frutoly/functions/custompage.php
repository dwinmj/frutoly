<?php
if (get_row_layout() == 'specialist_question_cards'):
    ?>


    <?php
    /**
     *  Get all the questions and build array data.
     */
    $_questions = array();
    $_doctors = array();
    $doctor_ids = array();
    $args = array(
        'post_type' => 'specialist_question',
        'posts_per_page' => -1
    );
    $specialist_question = new WP_Query($args);

    while ($specialist_question->have_posts()) : $specialist_question->the_post();
        $question_fields = get_fields();
        $doctor_id = $question_fields["doctor"][0];
        $doctor_ids[] = $doctor_id;
        $_id = get_the_ID();
        $_questions[] = array(
            'post_id' => $_id,
            'question' => get_the_title(),
            'answer' => wp_strip_all_tags($question_fields["answer"]),
            'doctor' => $question_fields["doctor"][0],
            'category' => $question_fields["category"]
        );
    endwhile;
    /**
     *  Now we have the list of questions lets query the doctors post type from the list
     *  and let's get them in the order required
     */

    $args = array(
        'post_type' => array('our_doctors'),
        'posts_per_page' => -1,
        'post_in' => $doctor_ids
    );
    $doctors_query = new WP_Query($args);

    while ($doctors_query->have_posts()) : $doctors_query->the_post();
        $doctor_post_id = get_the_ID();
        $doctor_fields = get_fields();

        if (in_array($doctor_post_id, $doctor_ids)) {
            $_doctors[$doctor_post_id] = array(
                'doctor_post_id' => $doctor_post_id,
                'bio_image' => $doctor_fields['bio_image']['url'],
                'doctor_link' => $doctor_fields['bio_image']['link'],
                'first_name' => $doctor_fields['first_name'],
                'middle_name' => $doctor_fields['middle_name'],
                'last_name' => $doctor_fields['last_name'],
                'suffix' => $doctor_fields['suffix'],
                'actual_id' => $doctor_fields['bio_image']['uploaded_to']
            );

        }
    endwhile;
    $term_cat = array();
    $term_cat['upper-extremities'] = array(
        'order' => 0,
        'title' => 'Hand &amp; Upper Extremities',
        'image' => '/wp-content/uploads/2023/06/AccordionImg_Hand-Upper@2x.png',
        'img_alt' => 'a circle targeting the hand',
        'desc' => 'Treatments from shoulders to fingertips'
    );
    $term_cat['hips'] = array(
        'order' => 1,
        'title' => 'Hip',
        'image' => '/wp-content/uploads/2023/02/AccordionImg_Knee@2x.png',
        'img_alt' => 'a circle targeting the Hip',
        'desc' => 'When a replacement is necessary'
    );
    $term_cat['knee'] = array(
        'order' => 2,
        'title' => 'Knee',
        'image' => '/wp-content/uploads/2023/02/AccordionImg_Knee@2x.png',
        'img_alt' => 'a circle targeting the Knee',
        'desc' => 'There are many causes and innovative treatments'
    );
    $term_cat['joint-replacement'] = array(
        'order' => 3,
        'title' => 'Joint Replacement',
        'image' => '/wp-content/uploads/2023/02/AccordionImg_Knee@2x.png',
        'img_alt' => 'a circle targeting the Joints',
        'desc' => 'Options to make you feel as good as new'
    );
    $term_cat['shoulder'] = array(
        'order' => 4,
        'title' => 'Shoulder',
        'image' => '/wp-content/uploads/2023/02/AccordionImg_Shoulder@2x.png',
        'img_alt' => 'a circle targeting the Shoulder',
        'desc' => "Raise your hand if you can’t raise your hand"
    );
    $term_cat['sports-medicine'] = array(
        'order' => 5,
        'title' => 'Sports Medicine',
        'image' => '/wp-content/uploads/2023/02/AccordionImg_Shoulder@2x.png',
        'img_alt' => 'a circle targeting the sport players Joint',
        'desc' => "Get back in the game with our specialists’ care"
    );
    $term_cat['acupuncture'] = array(
        'order' => 6,
        'title' => 'Acupuncture',
        'image' => '/wp-content/uploads/2023/02/AccordionImg_SportsMed@2x.png',
        'img_alt' => 'a circle targeting Acupuncture in shoulder',
        'desc' => 'Ease pain and restore wellness, naturally'
    );
    $term_cat['clinical-trials'] = array(
        'order' => 7,
        'title' => 'Clinical Trials',
        'image' => '/wp-content/uploads/2023/06/AccordionImg_Medical@2x.png',
        'img_alt' => 'a circle targeting the latest medical trials',
        'desc' => 'Pioneering new treatments'
    );


    foreach ($_questions as $qa) {
        $term_cat[$qa['category']->slug]['data'][] = array(
            'term_id' => $qa['category']->term_id,
            'question' => $qa,
            'doctor' => $_doctors[$qa['doctor']]
        );
    }


    /*function sort_by_order($a, $b) {
      return strcmp($a["order"], $b["order"]);
    }
    usort($term_cat, 'sort_by_order');*/
    function string_limit($string, $limit = 275)
    {
        if (strlen($string) > $limit) {
            $sub_str = substr($string, 0, $limit);
            $ending = strrpos($sub_str, ' ');
            $string = $ending ? substr($sub_str, 0, $ending) : substr($sub_str, 0);
            $string .= '...';
        }
        return $string;
    }

    ?>

    <style>

        section.specialist-question-cards {
            margin-bottom: 4em;
        }

        .specialist-question-section {
            width: 100%;
            position: relative;
            margin-bottom: 4em;
        }

        .specialist-question-header {
            position: relative;
            width: 100%;
            min-height: 100px;
            height: 100px;
            border: 1px solid #c0c9cc;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        img.question-icon {
            position: absolute;
            bottom: 0;
            left: 2em;
            width: 125px;
        }

        div.question-topic {
            width: calc(100% - (4em + 125px));
        }

        div.question-topic h2 {
            color: #023446;
            font-size: 28px;
        }

        div.question-topic p {
            color: #006a8f;
            font-size: 14px;
            font-style: italic;
        }

        div.icon-spacer {
            width: calc(4em + 125px);
        }

        button.section-toggle {
            width: 2em;
            height: 2em;
            background-color: #f79c33;
            color: white;
            margin-right: 2em;
            cursor: pointer;
        }

        div.specialist-question-body {
            margin-top: 2em;
            width: 100%;
            display: none;
            grid-template-columns: repeat(3, 1fr);
            gap: 1em;
        }

        div.grid-item {
            position: relative;
            width: 100%;
            height: 350px;
            /*padding: 1em;*/
            background-color: transparent;
            perspective: 1000px;
        }

        div.grid-item-inner, div.grid-item-front {
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        div.grid-item-inner {
            position: relative;
            transition: transform 0.8s;
            transform-style: preserve-3d;
            -webkit-transition: transform 0.8s;
            -webkit-transform-style: preserve-3d;
            width: 100%;
            height: 350px;
        }

        div.grid-item-front, div.grid-item-back {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 350px;
            padding: 1em;
            -webkit-backface-visibility: hidden; /* Safari */
            backface-visibility: hidden;
        }

        div.grid-item-front, div.grid-item-back {
            border: 1px solid #c0c9cc;
            box-shadow: 5px 5px 5px 1px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        div.grid-item-front {
            transform: translateZ(-1px);
            -webkit-transform: translateZ(-1px);
        }

        div.grid-item-back {
            padding: 2.5em;
        }

        div.gq-wrap {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: calc(100% - 35px - 2em);
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        div.grid-item-back {
            transform: rotateY(180deg) translateZ(1px);
            -webkit-transform: rotateY(180deg) translateZ(1px);
        }

        button.close-card {
            font-size: 1.25em;
            color: #0e688e;
            cursor: pointer !important;
            /*z-index: 1000;*/
            transition: transform .4s ease-in-out;
            -webkit-transition: transform .4s ease-in-out;
        }

        button.close-card:hover {
            transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
        }

        /*
        div.grid-item:hover div.grid-item-inner{
            transform: rotateY(180deg);
        }*/

        h2.grid-question {
            font-size: 28px;
            color: #006a8f;
            text-align: center;
            padding-left: 1em;
            padding-right: 1em;
        }

        q.grid-question {
            font-family: "Raleway", sans-serif;
            color: #023446;
            font-weight: 500;
            font-size: 18px;
            line-height: 26px;
        }

        button.view-answer {
            position: absolute;
            left: 50%;
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
            bottom: 2em;
            font-size: 14px;
        }

        div.grid-doc-info {
            width: 100%;
            display: inline-flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1em;
        }

        div.pfp-wrap {
            position: relative;
            width: 50px;
            height: 50px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 4px #f79c33 solid;
            border-radius: 60px;
            overflow: hidden !important;
        }

        img.doc-pfp {
            width: 100%;
            height: auto;
        }

        p.doc-name {
            font-family: "PT Serif", serif;
            color: #0e688e;
            margin-left: 1em;
        }

        div.modal-bg {
            display: none;
            width: 100vw;
            height: 100vh;
            background-color: #133344;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999997;
            opacity: .75;
        }

        div.grid-modal {
            position: fixed;
            top: 141px;
            left: 50%;
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
            width: 90%;
            height: calc(100vh - 242px);
            z-index: 999998;
        }

        div.grid-item-inner.active-card, div.grid-item-inner.active-card div.grid-item-front, div.grid-item-inner.active-card div.grid-item-back {
            height: auto;
        }

        img.icon-type2 {
            width: 150px;
            left: 0;
        }

        img.accup-icon {
            bottom: -20px;
        }

        @media screen and (max-width: 1100px) {
            div.specialist-question-body {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 700px) {
            div.question-topic {
                padding-left: 1em;
                padding-right: 1em;
            }

            div.question-topic h2, h2.grid-question {
                font-size: 20px;
                line-height: 20px;
            }

            div.question-topic p {
                margin-top: 5px;
                font-size: 12px;
                line-height: 14px;
            }

            div.specialist-question-body {
                grid-template-columns: repeat(1, 1fr);
            }

            img.question-icon, div.icon-spacer {
                display: none;
            }

            div.question-topic {
                width: calc(100% - 2em);
                padding-left: 2em;
            }

            .specialist-question-section {
                margin-bottom: 2em;
            }

            button.section-toggle {
                margin-right: 1em;
            }

            div.grid-item-inner, button.close-card {
                transition: transform 0s;
            }

            button.close-card:hover {
                transform: none;
                -webkit-transform: none;
            }

            h2.grid-question {
                font-size: 28px;
                line-height: 30px;
                text-indent: -0.4em;
                padding-left: 1.5em;
                padding-right: 2em;
                text-align: left;
            }

            div.grid-item, div.grid-item-inner,
            div.grid-item-front, div.grid-item-back {
                height: 300px;
            }

            div.gq-wrap {
                justify-content: flex-start;
            }
        }

        @media screen and (max-width: 500px) {
            button.btn.view-answer {
                left: 40px !important;
                -webkit-transform: translateX(0) !important;
                transform: translateX(0) !important;
            }
        }

    </style>
    <section class="specialist-question-cards module">
        <div class="grid-container">
            <?php foreach ($term_cat as $cat):
                echo "<!-- " . strtoupper($cat) . " ACCORDION -->";
                ?>
                <div class="specialist-question-section">
                    <div class="specialist-question-header">

                        <img class="question-icon icon-type2" src="<?php echo $cat['image']; ?>"
                             alt="<?php echo $cat['img_alt']; ?>" data-uw-rm-ima="ai">
                        <div class="icon-spacer"></div>
                        <div class="question-topic">
                            <h2>
                                <?php echo $cat['title']; ?>
                            </h2>
                            <p>
                                <?php echo $cat['desc']; ?>
                            </p>
                        </div>
                        <button class="section-toggle">
                            +
                        </button>
                    </div>
                    <div class="specialist-question-body">
                        <?php foreach ($cat['data'] as $_answer):
                            $t_question = $_answer['question'];
                            $t_doctor = $_answer['doctor'];
                            $middle_ = substr($t_doctor['middle_name'], 0, 1) ? substr($t_doctor['middle_name'], 0, 1) . ". " : '';
                            $doc_name = $t_doctor['first_name'] . " " . $middle_ . $t_doctor['last_name'] . ", " . $t_doctor['suffix'];
                            ?>

                            <div class="grid-item" id="gid-<?php echo $t_question['post_id']; ?>">
                                <div class="grid-item-inner" id="<?php echo $t_question['post_id']; ?>">
                                    <div class="grid-item-front">
                                        <div class="gq-wrap"><h2 class="grid-question">
                                                "<?php echo trim($t_question['question'], '?'); ?>?" </h2></div>
                                        <button class="btn view-answer"
                                                onclick="viewCard(<?php echo $t_question['post_id']; ?>);"
                                                data-uw-rm-kbnav="click">View Answer
                                        </button>
                                    </div>
                                    <div class="grid-item-back">
                                        <div class="grid-doc-info">
                                            <div style="display: inline-flex; justify-content: center; align-items: center;">
                                                <div class="pfp-wrap">
                                                    <img data-idd="<?php echo $t_doctor['doctor_post_id']; ?>"
                                                         class="doc-pfp" src="<?php echo $t_doctor['bio_image']; ?>"
                                                         role="presentation" alt="" data-uw-rm-ima="un">
                                                </div>
                                                <a href="<?php echo $t_doctor['doctor_link']; ?>" target="_blank"
                                                   data-uw-rm-brl="false"
                                                   aria-label="<?php echo $doc_name ?> - opens in new tab"
                                                   data-uw-rm-ext-link=""
                                                   uw-rm-external-link-id="<?php echo $t_doctor['doctor_link']; ?>"><p
                                                            class="doc-name"><?php echo $doc_name; ?></p></a>
                                            </div>
                                            <button class="close-card"
                                                    onclick="modalClose(<?php echo $t_question['post_id']; ?>);"
                                                    data-uw-rm-kbnav="click">✕
                                            </button>
                                        </div>
                                        <p class="grid-question">
                                            “<?php echo string_limit(wp_strip_all_tags($t_question['answer']), 270); ?>
                                            ”</p>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
                <?php
                echo "<!-- /" . strtoupper($cat) . " ACCORDION -->";
            endforeach; ?>
        </div>
    </section>
    <script>

        //Global window width
        let globalWidth;

        //View card function - based on post id
        function viewCard(id) {
            if (globalWidth > 700) {
                flipOpen(id);
            } else if (globalWidth <= 700) {
                modalOpen(id);
            }
        }

        //Close card function - based on post id
        function closeCard(id) {
            if (globalWidth > 700) {
                flipClose(id);
            } else if (globalWidth <= 700) {
                modalClose(id);
            }
        }

        //Desktop card open
        function flipOpen(id) {
            let sel = "div#" + id;
            $(sel).css('transform', 'rotateY(-180deg)');
        }

        //Mobile card open
        function modalOpen(id) {

            /*
             * 1. display blue overlay
             * 2. add extra class to sel
             * 3. rotate & display sel
             * */

            //Grid item
            let gi = "div.grid-item#gid-" + id;
            //Grid item inner
            let sel = "div#" + id;

            $(gi).addClass('grid-modal');
            $(sel).addClass('active-card');
            $(sel).css('transform', 'rotateY(-180deg)');
            $('#modal-bg').css('display', 'block');
        }

        //Desktop card close
        function flipClose(id) {
            let sel = "div#" + id;
            $(sel).css('transform', 'rotateY(0deg)');
        }

        //Mobile card close
        function modalClose(id) {
            //Grid item
            let gi = "div.grid-item#gid-" + id;
            //Grid item inner
            let sel = "div#" + id;
            $(gi).removeClass('grid-modal');
            $(sel).removeClass('active-card');
            $(sel).css('transform', 'rotateY(0deg)');
            $('#modal-bg').css('display', 'none');
        }


        $(document).ready(function () {
            //Run accordion
            accordion();
            //Set global width
            globalWidth = $(window).width();

            console.log(globalWidth);
            console.log("hello");
        });

        $(window).resize(function () {
            //Set global width on resize
            globalWidth = $(window).width();
            console.log(globalWidth);
            console.log("hello");
        });

        //Accordion toggling functionality

        function accordion() {
            let togMain = document.querySelectorAll('div.specialist-question-header');

            for (i = 0; i < togMain.length; i++) {

                togMain[i].addEventListener('click', function () {

                    let toggleBtn = this.childNodes[7];

                    if (toggleBtn.innerText == "+") {

                        $('div.specialist-question-body').not(this).slideUp();
                        $(this.nextElementSibling).slideDown();
                        $(this.nextElementSibling).css('display', 'grid');
                        $(toggleBtn).html("-");
                        $(toggleBtn).css('backgroundColor', '#0e688e');
                        $('button.section-toggle').not(toggleBtn).html("+");
                        $('button.section-toggle').not(toggleBtn).css('backgroundColor', '#f79c33');

                    } else if (toggleBtn.innerText == "-") {
                        $(this.nextElementSibling).slideUp();
                        $(toggleBtn).html("+");
                        $(toggleBtn).css('backgroundColor', '#f79c33');
                    }

                });

            }
        }

    </script>
<?php endif;