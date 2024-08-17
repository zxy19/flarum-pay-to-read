import app from 'flarum/admin/app';

app.initializers.add('xypp/pay-to-read', () => {
  app.extensionData
    .for('xypp-pay-to-read')
    .registerPermission(
      {
        icon: 'fas fa-eye-slash',
        label: app.translator.trans('xypp-pay-to-read.admin.permission.bypass'),
        permission: 'post.ptr-bypassPayment',
      },
      'view'
    );
});
