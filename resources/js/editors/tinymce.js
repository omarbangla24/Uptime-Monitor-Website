import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';
import 'tinymce/icons/default';

// You can skip importing skin/content CSS here because TinyMCE will load them
// from base_url below. (Keeps things simple & avoids iframe CSS issues)

// Plugins you actually use:
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/help';
import 'tinymce/plugins/wordcount';

export function initTiny(selector = '#content') {
  return tinymce.init({
    selector,
    height: 400,
    menubar: false,
    plugins:
      'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    toolbar:
      'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',

    // 🔑 Agree to the open-source GPL license (removes license manager error)
    license_key: 'gpl',

    // 📦 Tell TinyMCE where to load skins/plugins from (fixes 404s)
    base_url: '/vendor/tinymce', // because we copied to public/vendor/tinymce
    suffix: '.min',               // match the filenames in that folder

    // Optional dark mode (based on Tailwind .dark)
    skin: document.documentElement.classList.contains('dark') ? 'oxide-dark' : 'oxide',
    content_css: document.documentElement.classList.contains('dark') ? 'dark' : 'default',

    promotion: false,
    content_style: 'body{font-family:Helvetica,Arial,sans-serif;font-size:14px}',
  });
}
