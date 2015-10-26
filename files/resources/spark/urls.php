<?php

return function(ArrayIterator $urls, $routes, $app)
{
    $urls->append('/cities');
    $urls->append('/cities/san-francisco');
};

