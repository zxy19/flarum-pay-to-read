import app from 'flarum/forum/app';
import CommentPost from "flarum/forum/components/CommentPost";
import { extend } from "flarum/common/extend";
import TextEditor from "flarum/common/components/TextEditor";
import TextEditorButton from "flarum/common/components/TextEditorButton";
import {handlePtrBlock} from "./ptrBlockHandler";
app.initializers.add('xypp/pay-to-read', () => {
    // let payment = app.store.createRecord("payment");
    // payment.save({user_id:1}).then(console.log).catch(console.log);
    extend(CommentPost.prototype, "oncreate",handlePtrBlock);
    extend(TextEditor.prototype, "toolbarItems", function (items) {
        items.add(
          "pay-to-read",
          <TextEditorButton
            onclick={() => {
              this.attrs.composer.editor.insertAtCursor("[pay amount=1]"+
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
