// any CSS you import will output into a single css file (app.css in this case)
import './build-admin.scss';

// import bootstrap 5 js
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// import 'tinymce';
// import 'tinymce/models/dom/model';
// import 'tinymce/icons/default';
// import 'tinymce/themes/silver';

import '@softspring/cms-bundle/scripts/admin-cms';
import '@softspring/media-bundle/scripts/media-type';
import '@softspring/polymorphic-form-type/scripts/polymorphic-form-type';
import '@softspring/cms-module-collection/modules';

