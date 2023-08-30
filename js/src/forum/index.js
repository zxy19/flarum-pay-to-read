import app from 'flarum/forum/app';
import LogInModal from "flarum/forum/components/LogInModal";
import CommentPost from "flarum/forum/components/CommentPost";
import DiscussionPage from "flarum/forum/components/DiscussionPage";
import PayModal from './components/PayModal';
import { extend } from "flarum/common/extend";
import TextEditor from "flarum/common/components/TextEditor";
import TextEditorButton from "flarum/common/components/TextEditorButton";

app.initializers.add('xypp/pay-to-read', () => {
    // let payment = app.store.createRecord("payment");
    // payment.save({user_id:1}).then(console.log).catch(console.log);
    extend(CommentPost.prototype, "content", function () {
        if (app.current.matches(DiscussionPage)) {
            if (app.session.user) {
                $(".ptr-block.ptr-pay-btn")
                    .off("click")
                    .on("click", (e) => {
                        let box = $(e.currentTarget?.parentElement);
                        app.modal.show(PayModal, {
                            item_id: box.attr("data-id")
                        });
                    });
            } else {
                $(".ptr-block.ptr-pay-btn")
                    .off("click")
                    .on("click", () => app.modal.show(LogInModal));
            }
            $(".ptr-block").each((idx, element) => {
                $(element)
                    .find("span")
                    .text(
                        app.translator.trans("xypp-pay-to-read.forum.payment-req.text",
                            [$(element).attr("data-ammount")]).join("")
                    );
            });
        }
    });
    extend(TextEditor.prototype, "toolbarItems", function (items) {
        items.add(
          "pay-to-read",
          <TextEditorButton
            onclick={() => {
              this.attrs.composer.editor.insertAtCursor("[pay ammount=1]"+
              app.translator.trans("xypp-pay-to-read.forum.editor.help")+
              "[/pay]");
              const range = this.attrs.composer.editor.getSelectionRange();
              this.attrs.composer.editor.moveCursorTo(range[1] - 6);
            }}
            icon="fa fa-comment-dollar"
          >
            {app.translator.trans("xypp-pay-to-read.forum.editor.label")}
          </TextEditorButton>
        );
    });
});
