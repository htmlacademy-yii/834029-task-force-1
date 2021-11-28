Dropzone.autoDiscover = false;

var dropzone = new Dropzone(".dropzone", {
    url: window.location.href,
    maxFiles: 6,
    uploadMultiple: true,
    acceptedFiles: 'image/*',
    previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы" width="100" height="100"></a>'
});