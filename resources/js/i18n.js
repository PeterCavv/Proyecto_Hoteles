import { createI18n } from 'vue-i18n'

export default function setupI18n(translations, locale = 'es') {
    return createI18n({
        legacy: false,
        locale,
        messages: {
            [locale]: translations,
        },
    });
}
