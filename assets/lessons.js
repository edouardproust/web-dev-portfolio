import './sass/lessons.scss';

import hoveranimation from './js/animation/hoveranimation'; hoveranimation.exec();
import gridfilter from './js/gallery/gridfilter'; gridfilter.exec();
import gallery from './js/gallery/gallery'; gallery.exec(); // mandatory for filter animation to work on load

// TODO: activate lazyload on gallery