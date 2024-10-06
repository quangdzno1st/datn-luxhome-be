!function (e) {
    "use strict";
    AIZ.data = {
        csrf: e('meta[name="csrf-token"]').attr("content"),
        appUrl: e('meta[name="app-url"]').attr("content"),
        fileBaseUrl: e('meta[name="file-base-url"]').attr("content")
    }, AIZ.uploader = {
        data: {
            selectedFiles: [],
            selectedFilesObject: [],
            clickedForDelete: null,
            allFiles: [],
            multiple: !1,
            type: "all",
            next_page_url: null,
            prev_page_url: null
        }, removeInputValue: function (l, a, t) {
            var d = a.filter((function (e) {
                return e !== l
            }));
            d.length > 0 ? e(t).find(".file-amount").html(AIZ.uploader.updateFileHtml(d)) : t.find(".file-amount").html(AIZ.local.choose_file), e(t).find(".selected-files").val(d)
        }, removeAttachment: function () {
            e(document).on("click", ".remove-attachment", (function () {
                var l = e(this).closest(".file-preview-item").data("id"),
                    a = e(this).closest(".file-preview").prev('[data-toggle="aizuploader"]').find(".selected-files").val().split(",").map(Number);
                AIZ.uploader.removeInputValue(l, a, e(this).closest(".file-preview").prev('[data-toggle="aizuploader"]')), e(this).closest(".file-preview-item").remove()
            }))
        }, deleteUploaderFile: function () {
            e(".aiz-uploader-delete").each((function () {
                e(this).on("click", (function (l) {
                    l.preventDefault();
                    var a = e(this).data("id");
                    AIZ.uploader.data.clickedForDelete = a, e("#aizUploaderDelete").modal("show"), e(".aiz-uploader-confirmed-delete").on("click", (function (l) {
                        if (l.preventDefault(), 1 === l.detail) {
                            var a = AIZ.uploader.data.allFiles[AIZ.uploader.data.allFiles.findIndex((e => e.id === AIZ.uploader.data.clickedForDelete))];
                            e.ajax({
                                url: AIZ.data.appUrl + "/aiz-uploader/destroy/" + AIZ.uploader.data.clickedForDelete,
                                type: "DELETE",
                                dataType: "JSON",
                                data: {
                                    id: AIZ.uploader.data.clickedForDelete,
                                    _method: "DELETE",
                                    _token: AIZ.data.csrf
                                },
                                success: function () {
                                    AIZ.uploader.data.selectedFiles = AIZ.uploader.data.selectedFiles.filter((function (e) {
                                        return e !== AIZ.uploader.data.clickedForDelete
                                    })), AIZ.uploader.data.selectedFilesObject = AIZ.uploader.data.selectedFilesObject.filter((function (e) {
                                        return e !== a
                                    })), AIZ.uploader.updateUploaderSelected(), AIZ.uploader.getAllUploads(AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files"), AIZ.uploader.data.clickedForDelete = null, e("#aizUploaderDelete").modal("hide")
                                }
                            })
                        }
                    }))
                }))
            }))
        }, uploadSelect: function () {
            e(".aiz-uploader-select").each((function () {
                var l = e(this);
                l.on("click", (function (a) {
                    var t = e(this).data("value"),
                        d = AIZ.uploader.data.allFiles[AIZ.uploader.data.allFiles.findIndex((e => e.id === t))];
                    l.closest(".aiz-file-box-wrap").toggleAttr("data-selected", "true", "false"), AIZ.uploader.data.multiple || l.closest(".aiz-file-box-wrap").siblings().attr("data-selected", "false"), AIZ.uploader.data.selectedFiles.includes(t) ? (AIZ.uploader.data.selectedFiles = AIZ.uploader.data.selectedFiles.filter((function (e) {
                        return e !== t
                    })), AIZ.uploader.data.selectedFilesObject = AIZ.uploader.data.selectedFilesObject.filter((function (e) {
                        return e !== d
                    }))) : (AIZ.uploader.data.multiple || (AIZ.uploader.data.selectedFiles = [], AIZ.uploader.data.selectedFilesObject = []), AIZ.uploader.data.selectedFiles.push(t), AIZ.uploader.data.selectedFilesObject.push(d)), AIZ.uploader.addSelectedValue(), AIZ.uploader.updateUploaderSelected()
                }))
            }))
        }, updateFileHtml: function (e) {
            var l = "";
            if (e.length > 1) l = AIZ.local.files_selected; else l = AIZ.local.file_selected;
            return e.length + " " + l
        }, updateUploaderSelected: function () {
            e(".aiz-uploader-selected").html(AIZ.uploader.updateFileHtml(AIZ.uploader.data.selectedFiles))
        }, clearUploaderSelected: function () {
            e(".aiz-uploader-selected-clear").on("click", (function () {
                AIZ.uploader.data.selectedFiles = [], AIZ.uploader.addSelectedValue(), AIZ.uploader.addHiddenValue(), AIZ.uploader.resetFilter(), AIZ.uploader.updateUploaderSelected(), AIZ.uploader.updateUploaderFiles()
            }))
        }, resetFilter: function () {
            e('[name="aiz-uploader-search"]').val(""), e('[name="aiz-show-selected"]').prop("checked", !1), e('[name="aiz-uploader-sort"] option[value=newest]').prop("selected", !0)
        }, getAllUploads: function (l, a = null, t = null) {
            e(".aiz-uploader-all").html('<div class="align-items-center d-flex h-100 justify-content-center w-100"><div class="spinner-border" role="status"></div></div>');
            var d = {};
            null != a && a.length > 0 && (d.search = a), null != t && t.length > 0 ? d.sort = t : d.sort = "newest", e.get(l, d, (function (l, a) {
                "string" == typeof l && (l = JSON.parse(l)), AIZ.uploader.data.allFiles = l.data, AIZ.uploader.allowedFileType(), AIZ.uploader.addSelectedValue(), AIZ.uploader.addHiddenValue(), AIZ.uploader.updateUploaderFiles(), null != l.next_page_url ? (AIZ.uploader.data.next_page_url = l.next_page_url, e("#uploader_next_btn").removeAttr("disabled")) : e("#uploader_next_btn").attr("disabled", !0), null != l.prev_page_url ? (AIZ.uploader.data.prev_page_url = l.prev_page_url, e("#uploader_prev_btn").removeAttr("disabled")) : e("#uploader_prev_btn").attr("disabled", !0)
            }))
        }, showSelectedFiles: function () {
            e('[name="aiz-show-selected"]').on("change", (function () {
                e(this).is(":checked") ? AIZ.uploader.data.allFiles = AIZ.uploader.data.selectedFilesObject : AIZ.uploader.getAllUploads(AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files"), AIZ.uploader.updateUploaderFiles()
            }))
        }, searchUploaderFiles: function () {
            e('[name="aiz-uploader-search"]').on("keyup", (function () {
                var l = e(this).val();
                AIZ.uploader.getAllUploads(AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files", l, e('[name="aiz-uploader-sort"]').val())
            }))
        }, sortUploaderFiles: function () {
            e('[name="aiz-uploader-sort"]').on("change", (function () {
                var l = e(this).val();
                AIZ.uploader.getAllUploads(AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files", e('[name="aiz-uploader-search"]').val(), l)
            }))
        }, addSelectedValue: function () {
            for (var e = 0; e < AIZ.uploader.data.allFiles.length; e++) AIZ.uploader.data.selectedFiles.includes(AIZ.uploader.data.allFiles[e].id) ? AIZ.uploader.data.allFiles[e].selected = !0 : AIZ.uploader.data.allFiles[e].selected = !1
        }, addHiddenValue: function () {
            for (var e = 0; e < AIZ.uploader.data.allFiles.length; e++) AIZ.uploader.data.allFiles[e].aria_hidden = !1
        }, allowedFileType: function () {
            if ("all" !== AIZ.uploader.data.type) {
                let e = AIZ.uploader.data.type.split(",");
                AIZ.uploader.data.allFiles = AIZ.uploader.data.allFiles.filter((function (l) {
                    return e.includes(l.type)
                }))
            }
        }, updateUploaderFiles: function () {
            e(".aiz-uploader-all").html('<div class="align-items-center d-flex h-100 justify-content-center w-100"><div class="spinner-border" role="status"></div></div>');
            var l = AIZ.uploader.data.allFiles;
            setTimeout((function () {
                if (e(".aiz-uploader-all").html(null), l.length > 0) for (var a = 0; a < l.length; a++) {
                    var t = "";
                    t = "image" === l[a].type ? '<img src="' + AIZ.data.fileBaseUrl + l[a].file_name + '" class="img-fit">' : '<i class="la la-file-text"></i>';
                    var d = '<div class="aiz-file-box-wrap" aria-hidden="' + l[a].aria_hidden + '" data-selected="' + l[a].selected + '"><div class="aiz-file-box"><div class="card card-file aiz-uploader-select" title="' + l[a].file_original_name + "." + l[a].extension + '" data-value="' + l[a].id + '"><div class="card-file-thumb">' + t + '</div><div class="card-body"><h6 class="d-flex"><span class="text-truncate title">' + l[a].file_original_name + '</span><span class="ext flex-shrink-0">.' + l[a].extension + "</span></h6><p>" + AIZ.extra.bytesToSize(l[a].file_size) + "</p></div></div></div></div>";
                    e(".aiz-uploader-all").append(d)
                } else e(".aiz-uploader-all").html('<div class="align-items-center d-flex h-100 justify-content-center w-100 nav-tabs"><div class="text-center"><h3>No files found</h3></div></div>');
                AIZ.uploader.uploadSelect(), AIZ.uploader.deleteUploaderFile()
            }), 300)
        }, inputSelectPreviewGenerate: function (l) {
            l.find(".selected-files").val(AIZ.uploader.data.selectedFiles), l.next(".file-preview").html(null), AIZ.uploader.data.selectedFiles.length > 0 ? e.post(AIZ.data.appUrl + "/aiz-uploader/get_file_by_ids", {
                _token: AIZ.data.csrf,
                ids: AIZ.uploader.data.selectedFiles.toString()
            }, (function (e) {
                if (l.next(".file-preview").html(null), e.length > 0) {
                    l.find(".file-amount").html(AIZ.uploader.updateFileHtml(e));
                    for (var a = 0; a < e.length; a++) {
                        var t = "";
                        t = "image" === e[a].type ? '<img src="' + e[a].file_name + '" class="img-fit">' : '<i class="la la-file-text"></i>';
                        var d = '<div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="' + e[a].id + '" title="' + e[a].file_original_name + "." + e[a].extension + '"><div class="align-items-center align-self-stretch d-flex justify-content-center thumb">' + t + '</div><div class="col body"><h6 class="d-flex"><span class="text-truncate title">' + e[a].file_original_name + '</span><span class="flex-shrink-0 ext">.' + e[a].extension + "</span></h6><p>" + AIZ.extra.bytesToSize(e[a].file_size) + '</p></div><div class="remove"><button class="btn btn-sm btn-link remove-attachment" type="button"><i class="la la-close"></i></button></div></div>';
                        l.next(".file-preview").append(d)
                    }
                } else l.find(".file-amount").html(AIZ.local.choose_file)
            })) : l.find(".file-amount").html(AIZ.local.choose_file)
        }, editorImageGenerate: function (e) {
            if (AIZ.uploader.data.selectedFiles.length > 0) for (var l = 0; l < AIZ.uploader.data.selectedFiles.length; l++) {
                var a = AIZ.uploader.data.allFiles.findIndex((e => e.id === AIZ.uploader.data.selectedFiles[l])),
                    t = "";
                "image" === AIZ.uploader.data.allFiles[a].type && (t = '<img src="' + AIZ.data.fileBaseUrl + AIZ.uploader.data.allFiles[a].file_name + '">', e[0].insertHTML(t))
            }
        }, dismissUploader: function () {
            e("#aizUploaderModal").on("hidden.bs.modal", (function () {
                e(".aiz-uploader-backdrop").remove(), e("#aizUploaderModal").remove()
            }))
        }, trigger: function (l = null, a = "", t = "all", d = "", i = !1, r = null) {
            l = e(l);
            var o = d;
            AIZ.uploader.data.selectedFiles = "" !== o ? o.split(",").map(Number) : [], void 0 !== t && t.length > 0 && (AIZ.uploader.data.type = t), AIZ.uploader.data.multiple = !!i, e.post(AIZ.data.appUrl + "/aiz-uploader", {_token: AIZ.data.csrf}, (function (t) {
                e("body").append(t), e("#aizUploaderModal").modal("show"), AIZ.plugins.aizUppy(), AIZ.uploader.getAllUploads(AIZ.data.appUrl + "/aiz-uploader/get_uploaded_files", null, e('[name="aiz-uploader-sort"]').val()), AIZ.uploader.updateUploaderSelected(), AIZ.uploader.clearUploaderSelected(), AIZ.uploader.sortUploaderFiles(), AIZ.uploader.searchUploaderFiles(), AIZ.uploader.showSelectedFiles(), AIZ.uploader.dismissUploader(), e("#uploader_next_btn").on("click", (function () {
                    null != AIZ.uploader.data.next_page_url && (e('[name="aiz-show-selected"]').prop("checked", !1), AIZ.uploader.getAllUploads(AIZ.uploader.data.next_page_url))
                })), e("#uploader_prev_btn").on("click", (function () {
                    null != AIZ.uploader.data.prev_page_url && (e('[name="aiz-show-selected"]').prop("checked", !1), AIZ.uploader.getAllUploads(AIZ.uploader.data.prev_page_url))
                })), e(".aiz-uploader-search i").on("click", (function () {
                    e(this).parent().toggleClass("open")
                })), e('[data-toggle="aizUploaderAddSelected"]').on("click", (function () {
                    "input" === a ? AIZ.uploader.inputSelectPreviewGenerate(l) : "direct" === a && r(AIZ.uploader.data.selectedFiles), e("#aizUploaderModal").modal("hide")
                }))
            }))
        }, initForInput: function () {
            e(document).on("click", '[data-toggle="aizuploader"]', (function (l) {
                if (1 === l.detail) {
                    var a = e(this), t = a.data("multiple"), d = a.data("type"), i = a.find(".selected-files").val();
                    t = t || "", d = d || "", i = i || "", AIZ.uploader.trigger(this, "input", d, i, t)
                }
            }))
        }, previewGenerate: function () {
            e('[data-toggle="aizuploader"]').each((function () {
                var l = e(this), a = l.find(".selected-files").val();
                "" != a && e.post(AIZ.data.appUrl + "/aiz-uploader/get_file_by_ids", {
                    _token: AIZ.data.csrf,
                    ids: a
                }, (function (e) {
                    if (l.next(".file-preview").html(null), e.length > 0) {
                        l.find(".file-amount").html(AIZ.uploader.updateFileHtml(e));
                        for (var a = 0; a < e.length; a++) {
                            var t = "";
                            t = "image" === e[a].type ? '<img src="' + e[a].file_name + '" class="img-fit">' : '<i class="la la-file-text"></i>';
                            var d = '<div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="' + e[a].id + '" title="' + e[a].file_original_name + "." + e[a].extension + '"><div class="align-items-center align-self-stretch d-flex justify-content-center thumb">' + t + '</div><div class="col body"><h6 class="d-flex"><span class="text-truncate title">' + e[a].file_original_name + '</span><span class="ext flex-shrink-0">.' + e[a].extension + "</span></h6><p>" + AIZ.extra.bytesToSize(e[a].file_size) + '</p></div><div class="remove"><button class="btn btn-sm btn-link remove-attachment" type="button"><i class="la la-close"></i></button></div></div>';
                            l.next(".file-preview").append(d)
                        }
                    } else l.find(".file-amount").html(AIZ.local.choose_file)
                }))
            }))
        }
    }
}(jQuery);
