import Modal from 'flarum/common/components/Modal';
import app from 'flarum/forum/app';
import Button from 'flarum/components/Button';
import setRouteWithForcedRefresh from "flarum/common/utils/setRouteWithForcedRefresh";
import Alert from "flarum/common/components/Alert/Alert"
export default class PayModal extends Modal {
    loading = false;
    tipText = app.translator.trans('xypp-pay-to-read.forum.payment.loading');
    btnText = app.translator.trans('xypp-pay-to-read.forum.payment.loading');
    item_id = -1;
    className() {
        return 'Modal--small PayModal';
    }

    title() {
        return app.translator.trans('xypp-pay-to-read.forum.payment.title');
    }

    content() {
        return (
            <div className="Modal-body">
                {this.tipText}
                <div className='paymodal-btn'>
                    <Button class='Button Button--primary' loading={this.loading} disabled={this.loading} onclick={this.pay} data-id={this.item_id}>
                        {this.btnText}
                    </Button>
                </div>
            </div>
        );
    }
    onready() {
        this.loading = true;
        this.item_id = this.attrs['item_id'];
        app.request({
            url: app.forum.attribute('apiUrl') + '/pay-to-read/payment/',
            params: {
                id: this.attrs['item_id']
            },
            method: 'GET'
        }).then((result) => {
            if (result.code == "200") {
                this.loading = false;
                this.tipText = app.translator.trans('xypp-pay-to-read.forum.payment.tip', 
                [result.ammount,result.user_money]
                );
                this.btnText = app.translator.trans('xypp-pay-to-read.forum.payment.btn');
                m.redraw();
            } else {
                app.modal.close();
                app.alerts.show(
                    {type: 'error'},
                    app.translator.trans('xypp-pay-to-read.forum.error.' + result.status, [])
                );
            }
        }).catch((e)=>{
            app.alerts.show(
                {type: 'error'},
                app.translator.trans("xypp-pay-to-read.forum.error.internal_err")
            );
            app.modal.close();
        });

    }
    pay(event) {
        this.loading = true;
        app.request({
            url: app.forum.attribute('apiUrl') + '/pay-to-read/payment/pay',
            body: {
                id: $(event.currentTarget).attr("data-id")
            },
            method: 'POST'
        }).then((result) => {
            this.loading = false;
            if (result.data.attributes.error == "") {
                app.modal.close();
                setRouteWithForcedRefresh(app.history.getCurrent().url);
            } else {
                app.modal.close();
                app.alerts.show(
                    {type: 'error'},
                    app.translator.trans('xypp-pay-to-read.forum.error.' + result.data.attributes.error, [])
                );
            }
        }).catch((e)=>{
            console.log(e);
            app.alerts.show(
                {type: 'error'},
                app.translator.trans("xypp-pay-to-read.forum.error.internal_err")
            );
            app.modal.close();
        });
    }
}
