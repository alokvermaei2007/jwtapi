<?php

namespace Drupal\hr_smartcard\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the SimpleForm form controller.
 *
 * This example demonstrates a simple form with a singe text input element. We
 * extend FormBase which is the simplest form base class used in Drupal.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class TokenConfigForm extends ConfigFormBase {

    /**
     * Build the simple form.
     *
     * A build form method constructs an array that defines how markup and
     * other form elements are included in an HTML form.
     *
     * @param array $form
     *   Default form array structure.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Object containing current form state.
     *
     * @return array
     *   The render array defining the elements of the form.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {


        $config = $this->config('tokenConfigForm.settings');
        $form['apikey'] = [
            '#type' => 'textfield',
            '#title' => 'Api Key',
//            '#required' => TRUE,
            '#default_value' => $config->get('apikey'),
        ];
        $form['secretkey'] = [
            '#type' => 'textfield',
            '#title' => 'Secret Key',
//            '#required' => TRUE,
            '#default_value' => $config->get('secretkey'),
        ];
        $form['jwt_token'] = [
            '#type' => 'textarea',
            '#title' => 'Jwt Token',
//            '#required' => TRUE,
            '#default_value' => $config->get('jwt_token'),
        ];
        $form['api_url'] = [
            '#type' => 'textfield',
            '#title' => 'Api Url',
//            '#required' => TRUE,
            '#default_value' => $config->get('api_url'),
        ];
        $form['submit'] = [
            '#title' => '',
            '#type' => 'submit',
            '#value' => 'Submit',
        ];
        return $form;
    }

    /**
     * Getter method for Form ID.
     *
     * The form ID is used in implementations of hook_form_alter() to allow other
     * modules to alter the render array built by this form controller.  it must
     * be unique site wide. It normally starts with the providing module's name.
     *
     * @return string
     *   The unique ID of the form defined by this class.
     */
    public function getFormId() {
        return 'tokenConfigForm';
    }

    /**
     * Implements form validation.
     *
     * The validateForm method is the default method called to validate input on
     * a form.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Object describing the current state of the form.
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        // $title = $form_state->getValue('title');
        //    if (strlen($title) < 5) {
        //      // Set an error for the form element with a key of "title".
        //      $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long.'));
        //    }.
    }

    /**
     * Implements a form submit handler.
     *
     * The submitForm method is the default method called for any submit elements.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Object describing the current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        /*
         * This would normally be replaced by code that actually does something
         * with the title.
         */
        $this->configFactory->getEditable('tokenConfigForm.settings')
                // Set the submitted configuration setting.
                ->set('apikey', $form_state->getValue('apikey'))
                ->set('secretkey', $form_state->getValue('secretkey'))
                ->set('jwt_token', $form_state->getValue('jwt_token'))
                ->set('api_url', $form_state->getValue('api_url'))
                // You can set multiple configurations at once by making.
                ->save();

        parent::submitForm($form, $form_state);
    }

    /**
     *
     */
    protected function getEditableConfigNames() {
        
    }

}
