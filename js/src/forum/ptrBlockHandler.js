import app from "flarum/forum/app";
import LogInModal from "flarum/forum/components/LogInModal";
import DiscussionPage from "flarum/forum/components/DiscussionPage";
import PayModal from './components/PayModal';
import {transStr2Str} from "../common/transUtil"
export function handlePtrBlock(e) {
  console.log(e)
    if (app.current.matches(DiscussionPage)) {
        $(".ptr-block.ptr-payment-require.ptr-render").each((idx, element) => {
            $(element)
                .prepend(
                    $("<div></div>")
                        .addClass("ptr-tip-line")
                        .prepend(
                            $("<button></button>")
                                .text(app.translator.trans("xypp-pay-to-read.forum.payment-req.btn"))
                                .addClass("ptr-pay-btn Button Button--primary")
                        ).prepend(
                            $("<span></span>")
                                .addClass("ptr-pay-tip")
                                .text(
                                    app.translator.trans("xypp-pay-to-read.forum.payment-req.text",
                                        [$(element).attr("data-amount")]).join("")
                                )
                        )
                )
        })
        $(".ptr-block.ptr-notfound.ptr-render").each((idx, element) => {
            $(element)
                .prepend(
                    $("<div></div>")
                        .addClass("ptr-tip-line")
                        .prepend(
                            $("<span></span>")
                                .addClass("ptr-pay-tip")
                                .text(
                                    transStr2Str(app.translator.trans("xypp-pay-to-read.forum.payment-notfound",
                                        [$(element).attr("data-id")]))
                                )
                        )
                )
        })

        $(".ptr-block.ptr-render").each((idx, element) => {
            $(element).removeClass("ptr-render");
            $(element).prepend(
                $("<div></div>")
                    .addClass("ptr-payment-tip")
                    .text(app.translator.trans(
                        "xypp-pay-to-read.forum.paymentTip", [
                        $(element).attr("data-amount")
                    ])).append(
                        $("<div></div>")
                            .addClass("ptr-haspaid-tip")
                            .text(
                                $(element).attr("data-haspaid-cnt")
                                    ?
                                    transStr2Str(app.translator.trans(
                                        "xypp-pay-to-read.forum.haspaidTip", [
                                        $(element).attr("data-haspaid-cnt")
                                    ]))
                                    :
                                    app.translator.trans(
                                        "xypp-pay-to-read.forum.nopaidTip", [])
                            )
                    )
            )
        })
        if (app.session.user) {
            $(".ptr-block .ptr-pay-btn")
                .off("click")
                .on("click", (e) => {
                    let box = $(e.currentTarget.parentElement.parentElement);
                    app.modal.show(PayModal, {
                        item_id: box.attr("data-id")
                    });
                });
        } else {
            $(".ptr-block .ptr-pay-btn")
                .off("click")
                .on("click", () => app.modal.show(LogInModal));
        }
    }
}
