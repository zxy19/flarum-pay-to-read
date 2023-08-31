import app from 'flarum/admin/app';

app.initializers.add('xypp/pay-to-read', () => {
  app.extensionData
    .for('xypp-pay-to-read')
    .registerSetting(
      {
        setting: 'xypp.ptr.max-stack', // This is the key the settings will be saved under in the settings table in the database.
        label: app.translator.trans('xypp-pay-to-read.admin.settings.max-stack.label'), // The label to be shown letting the admin know what the setting does.
        help: app.translator.trans('xypp-pay-to-read.admin.settings.max-stack.desc'), // Optional help text where a longer explanation of the setting can go.
        type: 'int', // What type of setting this is, valid options are: boolean, text (or any other <input> tag type), and select. 
      },
      30 // Optional: Priority
    ).registerPermission(
      {
        icon: 'fas fa-eye-slash',
        label: app.translator.trans('xypp-pay-to-read.admin.permission.bypass'),
        permission: 'post.ptr-bypassPayment',
      },
      'view'
    );
});
