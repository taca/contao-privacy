<?php

\Contao\Controller::loadDataContainer('tl_privacy_protocol_entry');
\Contao\System::loadLanguageFile('tl_privacy_protocol_entry');

$dca = &$GLOBALS['TL_DCA']['tl_settings'];

/**
 * Palettes
 */
$dca['palettes']['default'] .= ';{privacy_legend},privacyProtocolCallbacks,privacyProtocolFieldMapping;';

/**
 * Fields
 */
$fields = [
    'privacyProtocolCallbacks'    => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolCallbacks'],
        'inputType' => 'multiColumnEditor',
        'eval'      => [
            'tl_class'          => 'long clr',
            'multiColumnEditor' => [
                'minRowCount' => 0,
                'fields'      => [
                    'table'    => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolTable'],
                        'inputType'        => 'select',
                        'options_callback' => ['HeimrichHannot\Haste\Dca\General', 'getDataContainers'],
                        'eval'             => ['mandatory' => true, 'includeBlankOption' => true, 'style' => 'width: 180px'],
                    ],
                    'callback' => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolCallback'],
                        'inputType' => 'select',
                        'options'   => ['oncreate_callback', 'onversion_callback', 'ondelete_callback'],
                        'eval'      => ['mandatory' => true, 'includeBlankOption' => true, 'style' => 'width: 140px'],
                    ],
                    'cmsScope'      => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_privacy_protocol_entry']['cmsScope'],
                        'inputType' => 'select',
                        'options'   => array_merge(
                            [\HeimrichHannot\Privacy\Backend\ProtocolEntry::CMS_SCOPE_BOTH],
                            \HeimrichHannot\Privacy\Backend\ProtocolEntry::CMS_SCOPES
                        ),
                        'reference' => &$GLOBALS['TL_LANG']['tl_privacy_protocol_entry']['reference'],
                        'eval'      => ['tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true, 'style' => 'width: 75px'],
                    ],
                    'archive'  => [
                        'label'      => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolArchive'],
                        'inputType'  => 'select',
                        'foreignKey' => 'tl_privacy_protocol_archive.title',
                        'eval'       => ['mandatory' => true, 'includeBlankOption' => true, 'chosen' => true, 'style' => 'width: 200px'],
                    ],
                    'type'     => $GLOBALS['TL_DCA']['tl_privacy_protocol_entry']['fields']['type'],
                ],
            ],
        ],
    ],
    'privacyProtocolFieldMapping' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolFieldMapping'],
        'inputType' => 'multiColumnEditor',
        'eval'      => [
            'tl_class'          => 'long clr',
            'multiColumnEditor' => [
                'minRowCount' => 0,
                'fields'      => [
                    'table'         => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolTable'],
                        'inputType' => 'text',
                        'eval'      => [
                            'mandatory' => true,
                            'style'     => 'width: 250px'
                        ],
                    ],
                    'entityField'   => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolEntityField'],
                        'inputType' => 'text',
                        'eval'      => [
                            'mandatory' => true,
                            'style'     => 'width: 250px'
                        ],
                    ],
                    'protocolField' => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_settings']['privacyProtocolField'],
                        'inputType'        => 'select',
                        'options_callback' => ['HeimrichHannot\Privacy\Backend\ProtocolEntry', 'getFieldsAsOptions'],
                        'exclude'          => true,
                        'eval'             => [
                            'includeBlankOption' => true,
                            'chosen'             => true,
                            'tl_class'           => 'w50',
                            'mandatory'          => true,
                            'style'              => 'width: 250px'
                        ],
                    ]
                ],
            ],
        ],
    ],
];

$fields['privacyProtocolCallbacks']['eval']['multiColumnEditor']['fields']['type']['eval']['style'] = 'width: 200px';
unset($fields['privacyProtocolCallbacks']['eval']['multiColumnEditor']['fields']['type']['label'][1]);
unset($fields['privacyProtocolCallbacks']['eval']['multiColumnEditor']['fields']['cmsScope']['label'][1]);

$dca['fields'] += $fields;