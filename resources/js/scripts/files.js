
(function ($) {
    // USE STRICT
    "use strict";
AIZ.data = {
    csrf: $('meta[name="csrf-token"]').attr("content"),
    appUrl: $('meta[name="app-url"]').attr("content"),
    fileBaseUrl: $('meta[name="file-base-url"]').attr("content"),
};

 AIZ.uploader = {
    data: {
        selectedFiles: [],
        selectedFilesObject: [],
        clickedForDelete: null,
        allFiles: [],
        multiple: false,
        type: "all",
        next_page_url: null,
        prev_page_url: null,
    },
    removeInputValue: function (id, array, elem) {
        var selected = array.filter(function (item) {
            return item !== id;
        });
        if (selected.length > 0) {
            $(elem)
                .find(".file-amount")
                .html(AIZ.uploader.updateFileHtml(selected));
        } else {
            elem.find(".file-amount").html(AIZ.local.choose_file);
        }
        $(elem).find(".selected-files").val(selected);
    },
    removeAttachment: function () {
        $(document).on("click",'.remove-attachment', function () {
            var value = $(this)
                .closest(".file-preview-item")
                .data("id");
            var selected = $(this)
                .closest(".file-preview")
                .prev('[data-toggle="aizuploader"]')
                .find(".selected-files")
                .val()
                .split(",")
                .map(Number);

            AIZ.uploader.removeInputValue(
                value,
                selected,
                $(this)
                    .closest(".file-preview")
                    .prev('[data-toggle="aizuploader"]')
            );
            $(this).closest(".file-preview-item").remove();
        });
    },
    deleteUploaderFile: function () {
        $(".aiz-uploader-delete").each(function () {
            $(this).on("click", function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                AIZ.uploader.data.clickedForDelete = id;
                $("#aizUploaderDelete").modal("show");

                $(".aiz-uploader-confirmed-delete").on("click", function (
                    e
                ) {
                    e.preventDefault();
                    if (e.detail === 1) {
                        var clickedForDeleteObject =
                            AIZ.uploader.data.allFiles[
                                AIZ.uploader.data.allFiles.findIndex(
                                    (x) =>
                                        x.id ===
                                        AIZ.uploader.data.clickedForDelete
                                )
                                ];
                        $.ajax({
                            url:
                                AIZ.data.appUrl +
                                "/aiz-uploader/destroy/" +
                                AIZ.uploader.data.clickedForDelete,
                            type: "DELETE",
                            dataType: "JSON",
                            data: {
                                id: AIZ.uploader.data.clickedForDelete,
                                _method: "DELETE",
                                _token: AIZ.data.csrf,
                            },
                            success: function () {
                                AIZ.uploader.data.selectedFiles = AIZ.uploader.data.selectedFiles.filter(
                                    function (item) {
                                        return (
                                            item !==
                                            AIZ.uploader.data
                                                .clickedForDelete
                                        );
                                    }
                                );
                                AIZ.uploader.data.selectedFilesObject = AIZ.uploader.data.selectedFilesObject.filter(
                                    function (item) {
                                        return (
                                            item !== clickedForDeleteObject
                                        );
                                    }
                                );
                                AIZ.uploader.updateUploaderSelected();
                                AIZ.uploader.getAllUploads(
                                    AIZ.data.appUrl +
                                    "/aiz-uploader/get_uploaded_files"
                                );
                                AIZ.uploader.data.clickedForDelete = null;
                                $("#aizUploaderDelete").modal("hide");
                            },
                        });
                    }
                });
            });
        });
    },
    uploadSelect: function () {
        $(".aiz-uploader-select").each(function () {
            var elem = $(this);
            elem.on("click", function (e) {
                var value = $(this).data("value");
                var valueObject =
                    AIZ.uploader.data.allFiles[
                        AIZ.uploader.data.allFiles.findIndex(
                            (x) => x.id === value
                        )
                        ];
                // console.log(valueObject);

                elem.closest(".aiz-file-box-wrap").toggleAttr(
                    "data-selected",
                    "true",
                    "false"
                );
                if (!AIZ.uploader.data.multiple) {
                    elem.closest(".aiz-file-box-wrap")
                        .siblings()
                        .attr("data-selected", "false");
                }
                if (!AIZ.uploader.data.selectedFiles.includes(value)) {
                    if (!AIZ.uploader.data.multiple) {
                        AIZ.uploader.data.selectedFiles = [];
                        AIZ.uploader.data.selectedFilesObject = [];
                    }
                    AIZ.uploader.data.selectedFiles.push(value);
                    AIZ.uploader.data.selectedFilesObject.push(valueObject);
                } else {
                    AIZ.uploader.data.selectedFiles = AIZ.uploader.data.selectedFiles.filter(
                        function (item) {
                            return item !== value;
                        }
                    );
                    AIZ.uploader.data.selectedFilesObject = AIZ.uploader.data.selectedFilesObject.filter(
                        function (item) {
                            return item !== valueObject;
                        }
                    );
                }
                AIZ.uploader.addSelectedValue();
                AIZ.uploader.updateUploaderSelected();
            });
        });
    },
    updateFileHtml: function (array) {
        var fileText = "";
        if (array.length > 1) {
            var fileText = AIZ.local.files_selected;
        } else {
            var fileText = AIZ.local.file_selected;
        }
        return array.length + " " + fileText;
    },
    updateUploaderSelected: function () {
        $(".aiz-uploader-selected").html(
            AIZ.uploader.updateFileHtml(AIZ.uploader.data.selectedFiles)
        );
    },
    clearUploaderSelected: function () {
        $(".aiz-uploader-selected-clear").on("click", function () {
            AIZ.uploader.data.selectedFiles = [];
            AIZ.uploader.addSelectedValue();
            AIZ.uploader.addHiddenValue();
            AIZ.uploader.resetFilter();
            AIZ.uploader.updateUploaderSelected();
            AIZ.uploader.updateUploaderFiles();
        });
    },
    resetFilter: function () {
        $('[name="aiz-uploader-search"]').val("");
        $('[name="aiz-show-selected"]').prop("checked", false);
        $('[name="aiz-uploader-sort"] option[value=newest]').prop(
            "selected",
            true
        );
    },
    getAllUploads: function (url, search_key = null, sort_key = null) {
        $(".aiz-uploader-all").html(
            '<div class="align-items-center d-flex h-100 justify-content-center w-100"><div class="spinner-border" role="status"></div></div>'
        );
        var params = {};
        if (search_key != null && search_key.length > 0) {
            params["search"] = search_key;
        }
        if (sort_key != null && sort_key.length > 0) {
            params["sort"] = sort_key;
        }
        else{
            params["sort"] = 'newest';
        }
        $.get(url, params, function (data, status) {
            //console.log(data);
            if(typeof data == 'string'){
                data = JSON.parse(data);
            }
            AIZ.uploader.data.allFiles = data.data;
            AIZ.uploader.allowedFileType();
            AIZ.uploader.addSelectedValue();
            AIZ.uploader.addHiddenValue();
            //AIZ.uploader.resetFilter();
            AIZ.uploader.updateUploaderFiles();
            if (data.next_page_url != null) {
                AIZ.uploader.data.next_page_url = data.next_page_url;
                $("#uploader_next_btn").removeAttr("disabled");
            } else {
                $("#uploader_next_btn").attr("disabled", true);
            }
            if (data.prev_page_url != null) {
                AIZ.uploader.data.prev_page_url = data.prev_page_url;
                $("#uploader_prev_btn").removeAttr("disabled");
            } else {
                $("#uploader_prev_btn").attr("disabled", true);
            }
        });
    },
    showSelectedFiles: function () {
        $('[name="aiz-show-selected"]').on("change", function () {
            if ($(this).is(":checked")) {
                // for (
                //     var i = 0;
                //     i < AIZ.uploader.data.allFiles.length;
                //     i++
                // ) {
                //     if (AIZ.uploader.data.allFiles[i].selected) {
                //         AIZ.uploader.data.allFiles[
                //             i
                //         ].aria_hidden = false;
                //     } else {
                //         AIZ.uploader.data.allFiles[
                //             i
                //         ].aria_hidden = true;
                //     }
                // }
                AIZ.uploader.data.allFiles =
                    AIZ.uploader.data.selectedFilesObject;
            } else {
                // for (
                //     var i = 0;
                //     i < AIZ.uploader.data.allFiles.length;
                //     i++
                // ) {
                //     AIZ.uploader.data.allFiles[
                //         i
                //     ].aria_hidden = false;
                // }
                AIZ.uploader.getAllUploads(
                    AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files"
                );
            }
            AIZ.uploader.updateUploaderFiles();
        });
    },
    searchUploaderFiles: function () {
        $('[name="aiz-uploader-search"]').on("keyup", function () {
            var value = $(this).val();
            AIZ.uploader.getAllUploads(
                AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files",
                value,
                $('[name="aiz-uploader-sort"]').val()
            );
            // if (AIZ.uploader.data.allFiles.length > 0) {
            //     for (
            //         var i = 0;
            //         i < AIZ.uploader.data.allFiles.length;
            //         i++
            //     ) {
            //         if (
            //             AIZ.uploader.data.allFiles[
            //                 i
            //             ].file_original_name
            //                 .toUpperCase()
            //                 .indexOf(value) > -1
            //         ) {
            //             AIZ.uploader.data.allFiles[
            //                 i
            //             ].aria_hidden = false;
            //         } else {
            //             AIZ.uploader.data.allFiles[
            //                 i
            //             ].aria_hidden = true;
            //         }
            //     }
            // }
            //AIZ.uploader.updateUploaderFiles();
        });
    },
    sortUploaderFiles: function () {
        $('[name="aiz-uploader-sort"]').on("change", function () {
            var value = $(this).val();
            AIZ.uploader.getAllUploads(
                AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files",
                $('[name="aiz-uploader-search"]').val(),
                value
            );

            // if (value === "oldest") {
            //     AIZ.uploader.data.allFiles = AIZ.uploader.data.allFiles.sort(
            //         function(a, b) {
            //             return (
            //                 new Date(a.created_at) - new Date(b.created_at)
            //             );
            //         }
            //     );
            // } else if (value === "smallest") {
            //     AIZ.uploader.data.allFiles = AIZ.uploader.data.allFiles.sort(
            //         function(a, b) {
            //             return a.file_size - b.file_size;
            //         }
            //     );
            // } else if (value === "largest") {
            //     AIZ.uploader.data.allFiles = AIZ.uploader.data.allFiles.sort(
            //         function(a, b) {
            //             return b.file_size - a.file_size;
            //         }
            //     );
            // } else {
            //     AIZ.uploader.data.allFiles = AIZ.uploader.data.allFiles.sort(
            //         function(a, b) {
            //             a = new Date(a.created_at);
            //             b = new Date(b.created_at);
            //             return a > b ? -1 : a < b ? 1 : 0;
            //         }
            //     );
            // }
            //AIZ.uploader.updateUploaderFiles();
        });
    },
    addSelectedValue: function () {
        for (var i = 0; i < AIZ.uploader.data.allFiles.length; i++) {
            if (
                !AIZ.uploader.data.selectedFiles.includes(
                    AIZ.uploader.data.allFiles[i].id
                )
            ) {
                AIZ.uploader.data.allFiles[i].selected = false;
            } else {
                AIZ.uploader.data.allFiles[i].selected = true;
            }
        }
    },
    addHiddenValue: function () {
        for (var i = 0; i < AIZ.uploader.data.allFiles.length; i++) {
            AIZ.uploader.data.allFiles[i].aria_hidden = false;
        }
    },
    allowedFileType: function () {
        if (AIZ.uploader.data.type !== "all") {
            let type = AIZ.uploader.data.type.split(',')
            AIZ.uploader.data.allFiles = AIZ.uploader.data.allFiles.filter(
                function (item) {
                    return type.includes(item.type);
                }
            );
        }
    },
    updateUploaderFiles: function () {
        $(".aiz-uploader-all").html(
            '<div class="align-items-center d-flex h-100 justify-content-center w-100"><div class="spinner-border" role="status"></div></div>'
        );

        var data = AIZ.uploader.data.allFiles;

        setTimeout(function () {
            $(".aiz-uploader-all").html(null);

            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    var thumb = "";
                    var hidden = "";
                    if (data[i].type === "image") {
                        thumb =
                            '<img src="' +
                            AIZ.data.fileBaseUrl +
                            data[i].file_name +
                            '" class="img-fit">';
                    } else {
                        thumb = '<i class="la la-file-text"></i>';
                    }
                    var html =
                        '<div class="aiz-file-box-wrap" aria-hidden="' +
                        data[i].aria_hidden +
                        '" data-selected="' +
                        data[i].selected +
                        '">' +
                        '<div class="aiz-file-box">' +
                        // '<div class="dropdown-file">' +
                        // '<a class="dropdown-link" data-toggle="dropdown">' +
                        // '<i class="la la-ellipsis-v"></i>' +
                        // "</a>" +
                        // '<div class="dropdown-menu dropdown-menu-right">' +
                        // '<a href="' +
                        // AIZ.data.fileBaseUrl +
                        // data[i].file_name +
                        // '" target="_blank" download="' +
                        // data[i].file_original_name +
                        // "." +
                        // data[i].extension +
                        // '" class="dropdown-item"><i class="la la-download mr-2"></i>Download</a>' +
                        // '<a href="#" class="dropdown-item aiz-uploader-delete" data-id="' +
                        // data[i].id +
                        // '"><i class="la la-trash mr-2"></i>Delete</a>' +
                        // "</div>" +
                        // "</div>" +
                        '<div class="card card-file aiz-uploader-select" title="' +
                        data[i].file_original_name +
                        "." +
                        data[i].extension +
                        '" data-value="' +
                        data[i].id +
                        '">' +
                        '<div class="card-file-thumb">' +
                        thumb +
                        "</div>" +
                        '<div class="card-body">' +
                        '<h6 class="d-flex">' +
                        '<span class="text-truncate title">' +
                        data[i].file_original_name +
                        "</span>" +
                        '<span class="ext flex-shrink-0">.' +
                        data[i].extension +
                        "</span>" +
                        "</h6>" +
                        "<p>" +
                        AIZ.extra.bytesToSize(data[i].file_size) +
                        "</p>" +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "</div>";

                    $(".aiz-uploader-all").append(html);
                }
            } else {
                $(".aiz-uploader-all").html(
                    '<div class="align-items-center d-flex h-100 justify-content-center w-100 nav-tabs"><div class="text-center"><h3>No files found</h3></div></div>'
                );
            }
            AIZ.uploader.uploadSelect();
            AIZ.uploader.deleteUploaderFile();
        }, 300);
    },
    inputSelectPreviewGenerate: function (elem) {
        elem.find(".selected-files").val(AIZ.uploader.data.selectedFiles);
        elem.next(".file-preview").html(null);

        if (AIZ.uploader.data.selectedFiles.length > 0) {

            $.post(
                AIZ.data.appUrl + "/aiz-uploader/get_file_by_ids",
                { _token: AIZ.data.csrf, ids: AIZ.uploader.data.selectedFiles.toString() },
                function (data) {

                    elem.next(".file-preview").html(null);

                    if (data.length > 0) {
                        elem.find(".file-amount").html(
                            AIZ.uploader.updateFileHtml(data)
                        );
                        for (
                            var i = 0;
                            i < data.length;
                            i++
                        ) {
                            var thumb = "";
                            if (data[i].type === "image") {
                                thumb =
                                    '<img src="' +
                                    data[i].file_name +
                                    '" class="img-fit">';
                            } else {
                                thumb = '<i class="la la-file-text"></i>';
                            }
                            var html =
                                '<div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="' +
                                data[i].id +
                                '" title="' +
                                data[i].file_original_name +
                                "." +
                                data[i].extension +
                                '">' +
                                '<div class="align-items-center align-self-stretch d-flex justify-content-center thumb">' +
                                thumb +
                                "</div>" +
                                '<div class="col body">' +
                                '<h6 class="d-flex">' +
                                '<span class="text-truncate title">' +
                                data[i].file_original_name +
                                "</span>" +
                                '<span class="flex-shrink-0 ext">.' +
                                data[i].extension +
                                "</span>" +
                                "</h6>" +
                                "<p>" +
                                AIZ.extra.bytesToSize(
                                    data[i].file_size
                                ) +
                                "</p>" +
                                "</div>" +
                                '<div class="remove">' +
                                '<button class="btn btn-sm btn-link remove-attachment" type="button">' +
                                '<i class="la la-close"></i>' +
                                "</button>" +
                                "</div>" +
                                "</div>";

                            elem.next(".file-preview").append(html);
                        }
                    } else {
                        elem.find(".file-amount").html(AIZ.local.choose_file);
                    }
                });
        } else {
            elem.find(".file-amount").html(AIZ.local.choose_file);
        }

        // if (AIZ.uploader.data.selectedFiles.length > 0) {
        //     elem.find(".file-amount").html(
        //         AIZ.uploader.updateFileHtml(AIZ.uploader.data.selectedFiles)
        //     );
        //     for (
        //         var i = 0;
        //         i < AIZ.uploader.data.selectedFiles.length;
        //         i++
        //     ) {
        //         var index = AIZ.uploader.data.allFiles.findIndex(
        //             (x) => x.id === AIZ.uploader.data.selectedFiles[i]
        //         );
        //         var thumb = "";
        //         if (AIZ.uploader.data.allFiles[index].type == "image") {
        //             thumb =
        //                 '<img src="' +
        //                 AIZ.data.appUrl +
        //                 "/public/" +
        //                 AIZ.uploader.data.allFiles[index].file_name +
        //                 '" class="img-fit">';
        //         } else {
        //             thumb = '<i class="la la-file-text"></i>';
        //         }
        //         var html =
        //             '<div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="' +
        //             AIZ.uploader.data.allFiles[index].id +
        //             '" title="' +
        //             AIZ.uploader.data.allFiles[index].file_original_name +
        //             "." +
        //             AIZ.uploader.data.allFiles[index].extension +
        //             '">' +
        //             '<div class="align-items-center align-self-stretch d-flex justify-content-center thumb">' +
        //             thumb +
        //             "</div>" +
        //             '<div class="col body">' +
        //             '<h6 class="d-flex">' +
        //             '<span class="text-truncate title">' +
        //             AIZ.uploader.data.allFiles[index].file_original_name +
        //             "</span>" +
        //             '<span class="ext">.' +
        //             AIZ.uploader.data.allFiles[index].extension +
        //             "</span>" +
        //             "</h6>" +
        //             "<p>" +
        //             AIZ.extra.bytesToSize(
        //                 AIZ.uploader.data.allFiles[index].file_size
        //             ) +
        //             "</p>" +
        //             "</div>" +
        //             '<div class="remove">' +
        //             '<button class="btn btn-sm btn-link remove-attachment" type="button">' +
        //             '<i class="la la-close"></i>' +
        //             "</button>" +
        //             "</div>" +
        //             "</div>";

        //         elem.next(".file-preview").append(html);
        //     }
        // } else {
        //     elem.find(".file-amount").html("Choose File");
        // }
    },
    editorImageGenerate: function (elem) {
        if (AIZ.uploader.data.selectedFiles.length > 0) {
            for (
                var i = 0;
                i < AIZ.uploader.data.selectedFiles.length;
                i++
            ) {
                var index = AIZ.uploader.data.allFiles.findIndex(
                    (x) => x.id === AIZ.uploader.data.selectedFiles[i]
                );
                var thumb = "";
                if (AIZ.uploader.data.allFiles[index].type === "image") {
                    thumb =
                        '<img src="' +
                        AIZ.data.fileBaseUrl +
                        AIZ.uploader.data.allFiles[index].file_name +
                        '">';
                    elem[0].insertHTML(thumb);
                    // console.log(elem);
                }
            }
        }
    },
    dismissUploader: function () {
        $("#aizUploaderModal").on("hidden.bs.modal", function () {
            $(".aiz-uploader-backdrop").remove();
            $("#aizUploaderModal").remove();
        });
    },
    trigger: function (
        elem = null,
        from = "",
        type = "all",
        selectd = "",
        multiple = false,
        callback = null
    ) {
        // $("body").append('<div class="aiz-uploader-backdrop"></div>');

        var elem = $(elem);
        var multiple = multiple;
        var type = type;
        var oldSelectedFiles = selectd;
        if (oldSelectedFiles !== "") {
            AIZ.uploader.data.selectedFiles = oldSelectedFiles
                .split(",")
                .map(Number);
        } else {
            AIZ.uploader.data.selectedFiles = [];
        }
        if ("undefined" !== typeof type && type.length > 0) {
            AIZ.uploader.data.type = type;
        }

        if (multiple) {
            AIZ.uploader.data.multiple = true;
        }else{
            AIZ.uploader.data.multiple = false;
        }

        // setTimeout(function() {
        $.post(
            AIZ.data.appUrl + "/aiz-uploader",
            { _token: AIZ.data.csrf },
            function (data) {
                $("body").append(data);
                $("#aizUploaderModal").modal("show");
                AIZ.plugins.aizUppy();
                AIZ.uploader.getAllUploads(
                    AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files",
                    null,
                    $('[name="aiz-uploader-sort"]').val()
                );
                AIZ.uploader.updateUploaderSelected();
                AIZ.uploader.clearUploaderSelected();
                AIZ.uploader.sortUploaderFiles();
                AIZ.uploader.searchUploaderFiles();
                AIZ.uploader.showSelectedFiles();
                AIZ.uploader.dismissUploader();

                $("#uploader_next_btn").on("click", function () {
                    if (AIZ.uploader.data.next_page_url != null) {
                        $('[name="aiz-show-selected"]').prop(
                            "checked",
                            false
                        );
                        AIZ.uploader.getAllUploads(
                            AIZ.uploader.data.next_page_url
                        );
                    }
                });

                $("#uploader_prev_btn").on("click", function () {
                    if (AIZ.uploader.data.prev_page_url != null) {
                        $('[name="aiz-show-selected"]').prop(
                            "checked",
                            false
                        );
                        AIZ.uploader.getAllUploads(
                            AIZ.uploader.data.prev_page_url
                        );
                    }
                });

                $(".aiz-uploader-search i").on("click", function () {
                    $(this).parent().toggleClass("open");
                });

                $('[data-toggle="aizUploaderAddSelected"]').on(
                    "click",
                    function () {
                        if (from === "input") {
                            AIZ.uploader.inputSelectPreviewGenerate(elem);
                        } else if (from === "direct") {
                            callback(AIZ.uploader.data.selectedFiles);
                        }
                        $("#aizUploaderModal").modal("hide");
                    }
                );
            }
        );
        // }, 50);
    },
    initForInput: function () {
        $(document).on("click",'[data-toggle="aizuploader"]', function (e) {
            if (e.detail === 1) {
                var elem = $(this);
                var multiple = elem.data("multiple");
                var type = elem.data("type");
                var oldSelectedFiles = elem.find(".selected-files").val();

                multiple = !multiple ? "" : multiple;
                type = !type ? "" : type;
                oldSelectedFiles = !oldSelectedFiles
                    ? ""
                    : oldSelectedFiles;

                AIZ.uploader.trigger(
                    this,
                    "input",
                    type,
                    oldSelectedFiles,
                    multiple
                );
            }
        });
    },
    previewGenerate: function(){
        $('[data-toggle="aizuploader"]').each(function () {
            var $this = $(this);
            var files = $this.find(".selected-files").val();
            if(files != ""){
                $.post(
                    AIZ.data.appUrl + "/aiz-uploader/get_file_by_ids",
                    { _token: AIZ.data.csrf, ids: files },
                    function (data) {
                        $this.next(".file-preview").html(null);

                        if (data.length > 0) {
                            $this.find(".file-amount").html(
                                AIZ.uploader.updateFileHtml(data)
                            );
                            for (
                                var i = 0;
                                i < data.length;
                                i++
                            ) {
                                var thumb = "";
                                if (data[i].type === "image") {
                                    thumb =
                                        '<img src="' +
                                        data[i].file_name +
                                        '" class="img-fit">';
                                } else {
                                    thumb = '<i class="la la-file-text"></i>';
                                }
                                var html =
                                    '<div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="' +
                                    data[i].id +
                                    '" title="' +
                                    data[i].file_original_name +
                                    "." +
                                    data[i].extension +
                                    '">' +
                                    '<div class="align-items-center align-self-stretch d-flex justify-content-center thumb">' +
                                    thumb +
                                    "</div>" +
                                    '<div class="col body">' +
                                    '<h6 class="d-flex">' +
                                    '<span class="text-truncate title">' +
                                    data[i].file_original_name +
                                    "</span>" +
                                    '<span class="ext flex-shrink-0">.' +
                                    data[i].extension +
                                    "</span>" +
                                    "</h6>" +
                                    "<p>" +
                                    AIZ.extra.bytesToSize(
                                        data[i].file_size
                                    ) +
                                    "</p>" +
                                    "</div>" +
                                    '<div class="remove">' +
                                    '<button class="btn btn-sm btn-link remove-attachment" type="button">' +
                                    '<i class="la la-close"></i>' +
                                    "</button>" +
                                    "</div>" +
                                    "</div>";

                                $this.next(".file-preview").append(html);
                            }
                        } else {
                            $this.find(".file-amount").html(AIZ.local.choose_file);
                        }
                    });
            }
        });
    }
};
})(jQuery);
