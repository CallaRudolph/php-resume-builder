<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/Job.php';

    session_start();

    if(empty($_SESSION['person_info'])) {
        $_SESSION['person_info'] = array();
    }

    if(empty($_SESSION['list_of_jobs'])) {
        $_SESSION['list_of_jobs'] = array();
    }

    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get('/', function() use ($app) {
        return $app['twig']->render('jobs.html.twig');
    });

    $app->post('/resume', function() use ($app) {
        $person = new Person($_POST['name'], $_POST['email']);
        $person->save();
        return $app['twig']->render('resume.html.twig', array('new_person' => Person::getAll()));
    });

    $app->get('/resume', function() use ($app) {
        return $app['twig']->render('resume.html.twig', array('jobs' => Job::getAll(), 'new_person' => Person::getAll()));
    });

    $app->post('/created_job', function() use ($app) {
        $job = new Job($_POST['company'], $_POST['title'], $_POST['dates'], $_POST['description'], $_POST['image_url']);
        $job->save();
        return $app['twig']->render('created_job.html.twig', array('new_job' => $job));
    });

    $app->post('/deleted_jobs', function() use ($app) {
        return $app['twig']->render('deleted_jobs.html.twig', array('delete_jobs'=> Job::deleteAll(), 'delete_person' => Person::deleteAll()));
    });

    return $app;
?>
