import './sass/posts.scss';
import './js/_lib/isotope';

import gridfilter from './js/gallery/gridfilter'; gridfilter.exec();
import gallery from './js/gallery/gallery'; gallery.exec(); // mandatory for filter animation to work on load

// TODO: activate lazyload on gallery