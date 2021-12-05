Dropzone.autoDiscover = false;

var dropzone = new Dropzone(".dropzone", {
    url: "/account/load-files",
    paramName: "files",
    maxFiles: 6,
    parallelUploads: 6,
    uploadMultiple: true,
    acceptedFiles: 'image/*',
    previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы" width="100" height="100"></a>'
});