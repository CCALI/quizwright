# QuizWright
## A quiz builder and formative assessment tool for legal education

CALI QuizWright is a tool for creating and publishing formative assessments for your class. QuizWright gives law teachers a web-based platform to create and manage personal question banks and quizzes for their students. When used with CALI's AutoPublish, LessonLive, and LessonLink features, QuizWright quizzes can be delivered to students and taken in real time in the classroom using the familiar CALI Lesson Viewer.

## simpleSAMLphp service provider

CALI Author uses simpleSAMLphp as its SAML service provider and authenticates using the CALI website as the SAML identity provider.

This integration expects QuizWright to be installed in a folder named `quizwright` in the project root folder (not `web/`!) of the `cali-drupal` codebase. The Apache virtual host for Drupal should include the following to make `quizwright` available from this location:

```apacheconf
# QuizWright configuration
Alias /quizwright /path/to/drupal/quizwright

<Directory /path/to/drupal/quizwright>
    Options FollowSymLinks
    AllowOverride None
    Require all granted
</Directory>
```

All of simpleSAMLphp's custom configurations for the CALI-Author SAML SP are in the `cali-drupal` codebase.

For QuizWright to use the service provider, the `USER_MGMNT` constant in `includes/config.php` should point to `includes/saml_user.php`.
