<?php
/* Template Name: HereWeGoal */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Here We Goal</title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/herewegoal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/public/background.png');"></div>
    <div class="question-container">
        <?php
        $args = array(
            'post_type' => 'question-hwg',
            'posts_per_page' => 1,
            'orderby' => 'rand'
        );

        $question_query = new WP_Query($args);

        if ($question_query->have_posts()) :
            while ($question_query->have_posts()) : $question_query->the_post();

                // Récupérer les champs personnalisés de la question
                $question_title = get_the_title();
                $reponse = get_field('reponse');
                $proposition1 = get_field('proposition_1');
                $proposition2 = get_field('proposition_2');
                $proposition3 = get_field('proposition_3');

                // Propositions de réponses
                $propositions = array($reponse, $proposition1, $proposition2, $proposition3);
                shuffle($propositions);

                // Afficher le contenu de la question
                ?>
                <div class="question">

                    <div class="top">
                        <h3 class="question-number"> Question 1 </h3>
                        <h3 class="score"> Score : <span id="score">0</span> </h3>
                    </div>

                    <h1 class="question-title"><?php echo $question_title; ?></h1>

                    <div class="question-answers">
                        <div class="question-answer" data-answer="<?php echo $propositions[0]; ?>"><?php echo $propositions[0]; ?></div>
                        <div class="question-answer" data-answer="<?php echo $propositions[1]; ?>"><?php echo $propositions[1]; ?></div>
                        <div class="question-answer" data-answer="<?php echo $propositions[2]; ?>"><?php echo $propositions[2]; ?></div>
                        <div class="question-answer" data-answer="<?php echo $propositions[3]; ?>"><?php echo $propositions[3]; ?></div>
                    </div>

                    <div class="question-validation">Valider</div>
                    <div class="question-validation-message" style="display:none;"></div>
                    <div class="question-next" style="display:none;">Question suivante</div>
                </div>
                <?php

            endwhile;
            wp_reset_postdata();

        endif;
        ?>
    </div>
    <script>
        // On implemente la selection de la reponse
        const questionAnswers = document.querySelectorAll('.question-answer');
        const questionValidation = document.querySelector('.question-validation');
        const questionNext = document.querySelector('.question-next');
        const score = document.getElementById('score');

        let currentAnswer = null;
        let currentScore = 0;

        questionAnswers.forEach((answer) => {
            answer.addEventListener('click', () => {
                questionAnswers.forEach((answer) => {
                    answer.classList.remove('active');
                });
                answer.classList.add('active');
                currentAnswer = answer.getAttribute('data-answer');
            });
        });

        questionValidation.addEventListener('click', () => {
            if (currentAnswer) {
                if (currentAnswer === '<?php echo $reponse; ?>') {
                    currentScore += 1;
                    score.innerText = currentScore;
                }
                
                questionValidation.style.display = 'none';

                // Ajouter la classe question-validation-message pour afficher le message de validation (Bonne ou Mauvaise réponse)
                const questionValidationMessage = document.querySelector('.question-validation-message');
                questionValidationMessage.style.display = "block";
                questionValidationMessage.innerText = currentAnswer === '<?php echo $reponse; ?>' ? 'Bonne réponse' : 'Mauvaise réponse';

                questionNext.style.display = 'block';

                //ajouter "Bonne réponse" à la réponse correcte
                // On recupere la reponse correcte
                const correctAnswer = document.querySelector(`.question-answer[data-answer="<?php echo $reponse; ?>"]`);
                correctAnswer.classList.add('correct-answer');

                // On ajoute le texte "Bonne réponse" à la reponse correcte
                const correctAnswerText = document.createElement('div');
                correctAnswerText.classList.add('correct-answer-text');
                correctAnswerText.textContent = 'Bonne réponse';
                correctAnswer.appendChild(correctAnswerText);
                correctAnswerText.style.fontSize = '0.8em';
                    
            }
        });

        questionNext.addEventListener('click', () => {
            window.location.reload();
        });

    </script>
    <script src="<?php echo get_template_directory_uri(); ?>/herewegoal.js"></script>
</body>

</html>
