$(document).ready(function () {

    console.log("[DA FORMS] da-forms.js loaded.");

    /*
    ============================================================
    CONFIG
    ============================================================
    */

    const API_URL = "../../api/routes.php";


    /*
    ============================================================
    ADD PRICE FORM
    ============================================================
    */

    $(document).off("submit.daAdd", "#formAddPrice");

    $(document).on("submit.daAdd", "#formAddPrice", function (e) {

        e.preventDefault();

        console.log("[DA FORMS] Add form submitted.");

        const form = this;

        const category =
            $(form).find('[name="category"]').val();

        const productName =
            $(form).find('[name="item_description"]').val();

        const unit =
            $(form).find('[name="unit"]').val();

        const srp =
            $(form).find('[name="srp_price"]').val();

        const prevailingPrice =
            $(form).find('[name="prevailing_price"]').val();


        console.log("[DA FORMS] ADD DATA:", {
            category: category,
            product_name: productName,
            unit_of_measure: unit,
            srp: srp,
            prevailing_price: prevailingPrice
        });


        if (!productName) {
            Swal.fire(
                "Required",
                "Commodity / Item Description is required.",
                "warning"
            );
            return;
        }

        if (!category) {
            Swal.fire(
                "Required",
                "Category is required.",
                "warning"
            );
            return;
        }

        if (!unit) {
            Swal.fire(
                "Required",
                "Unit Measurement is required.",
                "warning"
            );
            return;
        }

        if (
            srp === "" ||
            isNaN(parseFloat(srp))
        ) {
            Swal.fire(
                "Required",
                "Please enter a valid SRP.",
                "warning"
            );
            return;
        }

        if (
            prevailingPrice === "" ||
            isNaN(parseFloat(prevailingPrice))
        ) {
            Swal.fire(
                "Required",
                "Please enter a valid prevailing price.",
                "warning"
            );
            return;
        }


        /*
        --------------------------------------------------------
        IMPORTANT
        --------------------------------------------------------
        The current backend expects commodity_id.

        The Add modal in da.php does NOT contain a commodity_id.

        Therefore we will NOT send addPrice directly here yet.
        --------------------------------------------------------
        */

        console.log(
            "[DA FORMS] Add form validation passed."
        );

        Swal.fire(
            "Add Form",
            "The Add form is ready. We will connect this to the commodity API next.",
            "info"
        );
    });


    /*
    ============================================================
    EDIT PRICE FORM
    ============================================================
    */

    $(document).off("submit.daEdit", "#formEditPrice");

    $(document).on("submit.daEdit", "#formEditPrice", function (e) {

        e.preventDefault();

        console.log("[DA FORMS] Edit form submitted.");


        const form = this;


        /*
        --------------------------------------------------------
        GET VALUES
        --------------------------------------------------------
        */

        const id =
            $.trim(
                $(form)
                    .find('[name="entry_id"]')
                    .val()
            );


        const commodityId =
            $.trim(
                $(form)
                    .find('[name="commodity_id"]')
                    .val()
            );


        const agencyId =
            $.trim(
                $(form)
                    .find('[name="monitored_by_agency_id"]')
                    .val()
            );


        const prevailingPrice =
            $.trim(
                $(form)
                    .find('[name="prevailing_price"]')
                    .val()
            );


        const srp =
            $.trim(
                $(form)
                    .find('[name="srp_price"]')
                    .val()
            );


        /*
        --------------------------------------------------------
        DEBUG
        --------------------------------------------------------
        */

        console.log(
            "[DA FORMS] EDIT DATA:",
            {
                id: id,
                commodity_id: commodityId,
                monitored_by_agency_id: agencyId,
                prevailing_price: prevailingPrice,
                srp_price: srp
            }
        );


        /*
        --------------------------------------------------------
        VALIDATION
        --------------------------------------------------------
        */

        if (
            id === "" ||
            !/^\d+$/.test(id)
        ) {

            Swal.fire(
                "Invalid Record",
                "The price record ID is missing or invalid.",
                "error"
            );

            return;
        }


        if (
            commodityId === "" ||
            !/^\d+$/.test(commodityId)
        ) {

            Swal.fire(
                "Invalid Commodity",
                "The commodity ID is missing or invalid.",
                "error"
            );

            return;
        }


        if (
            agencyId === "" ||
            !/^\d+$/.test(agencyId)
        ) {

            Swal.fire(
                "Invalid Agency",
                "The agency ID is missing or invalid.",
                "error"
            );

            return;
        }


        if (
            prevailingPrice === "" ||
            isNaN(parseFloat(prevailingPrice))
        ) {

            Swal.fire(
                "Invalid Price",
                "Please enter a valid prevailing monitored price.",
                "warning"
            );

            return;
        }


        if (
            srp === "" ||
            isNaN(parseFloat(srp))
        ) {

            Swal.fire(
                "Invalid SRP",
                "Please enter a valid SRP.",
                "warning"
            );

            return;
        }


        if (parseFloat(prevailingPrice) < 0) {

            Swal.fire(
                "Invalid Price",
                "Prevailing price cannot be negative.",
                "warning"
            );

            return;
        }


        if (parseFloat(srp) < 0) {

            Swal.fire(
                "Invalid SRP",
                "SRP cannot be negative.",
                "warning"
            );

            return;
        }


        /*
        ========================================================
        CONFIRM UPDATE
        ========================================================
        */

        Swal.fire({

            title: "Update DA Price Entry?",

            text:
                "The prevailing price and SRP will be updated.",

            icon: "question",

            showCancelButton: true,

            confirmButtonText:
                "Yes, Update",

            cancelButtonText:
                "Cancel"

        }).then(function (result) {

            if (!result.isConfirmed) {
                return;
            }


            /*
            ====================================================
            PREPARE REQUEST
            ====================================================
            */

            const requestData = {

                resource: "price",

                action: "update",

                id: id,

                commodity_id: commodityId,

                monitored_by_agency_id: agencyId,

                prevailing_price:
                    parseFloat(prevailingPrice),

                srp:
                    parseFloat(srp)

            };


            console.log(
                "[DA FORMS] UPDATE REQUEST:",
                requestData
            );


            /*
            ====================================================
            DISABLE BUTTON
            ====================================================
            */

            const submitButton =
                $(form).find(
                    'button[type="submit"]'
                );

            const originalButtonHtml =
                submitButton.html();


            submitButton
                .prop("disabled", true)
                .html(
                    '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Updating...'
                );


            /*
            ====================================================
            SEND UPDATE REQUEST
            ====================================================
            */

            $.ajax({

                url: API_URL,

                method: "POST",

                dataType: "json",

                data: requestData,

                success: function (response) {

                    console.log(
                        "[DA FORMS] UPDATE RESPONSE:",
                        response
                    );


                    if (
                        response &&
                        response.status === "success"
                    ) {

                        /*
                        ----------------------------------------
                        CLOSE MODAL
                        ----------------------------------------
                        */

                        $("#modalEditPrice")
                            .modal("hide");


                        /*
                        ----------------------------------------
                        SUCCESS MESSAGE
                        ----------------------------------------
                        */

                        Swal.fire({

                            icon: "success",

                            title: "Updated!",

                            text:
                                response.message ||
                                "DA price entry updated successfully.",

                            timer: 1800,

                            showConfirmButton: false

                        });


                        /*
                        ----------------------------------------
                        RELOAD DATATABLE
                        ----------------------------------------
                        */

                        setTimeout(function () {

                            if (
                                typeof window.daTable !==
                                "undefined" &&
                                window.daTable
                            ) {

                                window.daTable
                                    .ajax
                                    .reload(
                                        null,
                                        false
                                    );

                            } else if (
                                $.fn.DataTable.isDataTable(
                                    "#tblPriceMonitoring"
                                )
                            ) {

                                $("#tblPriceMonitoring")
                                    .DataTable()
                                    .ajax
                                    .reload(
                                        null,
                                        false
                                    );

                            }

                        }, 300);


                    } else {

                        Swal.fire({

                            icon: "error",

                            title: "Update Failed",

                            text:
                                (
                                    response &&
                                    response.message
                                )
                                ||
                                "Unable to update the DA price entry."

                        });

                    }

                },


                error: function (
                    xhr,
                    status,
                    error
                ) {

                    console.error(
                        "[DA FORMS] UPDATE AJAX ERROR:",
                        {
                            status: status,
                            error: error,
                            httpStatus:
                                xhr.status,
                            response:
                                xhr.responseText
                        }
                    );


                    let message =
                        "Unable to update the DA price entry.";


                    /*
                    ------------------------------------------------
                    TRY TO READ JSON ERROR
                    ------------------------------------------------
                    */

                    if (xhr.responseJSON) {

                        if (
                            xhr.responseJSON.message
                        ) {

                            message =
                                xhr.responseJSON.message;
                        }

                    } else if (
                        xhr.responseText
                    ) {

                        try {

                            const parsed =
                                JSON.parse(
                                    xhr.responseText
                                );

                            if (
                                parsed.message
                            ) {

                                message =
                                    parsed.message;
                            }

                        } catch (parseError) {

                            console.error(
                                "[DA FORMS] Could not parse server response:",
                                parseError
                            );

                        }
                    }


                    Swal.fire({

                        icon: "error",

                        title: "Server Error",

                        text: message

                    });

                },


                complete: function () {

                    submitButton
                        .prop("disabled", false)
                        .html(
                            originalButtonHtml
                        );

                }

            });

        });

    });


    /*
    ============================================================
    EDIT FORM RESET
    ============================================================
    */

    $("#modalEditPrice").on(
        "hidden.bs.modal",
        function () {

            console.log(
                "[DA FORMS] Edit modal closed."
            );

        }
    );


});