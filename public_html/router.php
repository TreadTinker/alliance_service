<?php
$router->add('GET', '/admin/candidates/export', ['AdminCandidatesController', 'export']);
$router->add('GET', '/admin/candidates/add', ['AdminCandidatesController', 'add']);
$router->add('POST', '/admin/candidates/add', ['AdminCandidatesController', 'add']);
$router->add('GET', '/admin/candidates/{id}/edit', ['AdminCandidatesController', 'edit']);
$router->add('POST', '/admin/candidates/{id}/edit', ['AdminCandidatesController', 'edit']);
$router->add('GET', '/admin/candidates/{id}/verifications', ['AdminCandidatesController', 'verifications']);