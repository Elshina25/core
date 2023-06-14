const plugin = require('tailwindcss/plugin')

/**
 * TODO: Идеально было бы сделать такую структуру со связкой брейкпоинтов
 */
// addComponents({
//   ttl: {
//     fontFamily: theme('fontFamily.title'),
//     fontWeight: theme('fontWeight.semibold'),
//     letterSpacing: theme('letterSpacing.2'),
//     lineHeight: {
//       DEFAULT: theme('lineHeight.130'),
//       2xl: theme('lineHeight.120')
//     },
//     fontSize: {
//       DEFAULT: theme('fontSize.30'),
//       md: theme('fontSize.60'),
//       2xl: theme('fontSize.100')
//     }
//   }
// }

module.exports = plugin(function ({ addComponents, theme }) {
  const md = `@media (min-width: ${theme('screens.md')})`
  const xl = `@media (min-width: ${theme('screens.xl')})`

  addComponents({
    /* Style Title */
    '.ttl': {
      fontFamily: theme('fontFamily.title'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.2'),
      fontSize: theme('fontSize.30'),

      [md]: {
        fontSize: theme('fontSize.60')
      },

      [xl]: {
        fontSize: '70px',
        lineHeight: theme('lineHeight.120')
      }
    },
    '.t-article': {
      fontFamily: theme('fontFamily.title'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.2'),
      fontSize: theme('fontSize.20'),

      [md]: {
        lineHeight: theme('lineHeight.120'),
        letterSpacing: theme('letterSpacing.0')
      },

      [xl]: {
        fontSize: theme('fontSize.40')
      }
    },
    '.t-h1': {
      fontFamily: theme('fontFamily.title'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.24'),

      [md]: {
        fontSize: theme('fontSize.40')
      },

      [xl]: {
        fontSize: theme('fontSize.50')
      }
    },
    '.t-h2': {
      fontFamily: theme('fontFamily.title'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.20'),

      [md]: {
        fontSize: theme('fontSize.30')
      },

      [xl]: {
        fontSize: theme('fontSize.30')
      }
    },
    '.t-h3': {
      fontFamily: theme('fontFamily.title'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.18'),

      [md]: {
        fontSize: theme('fontSize.20')
      },

      [xl]: {
        fontSize: theme('fontSize.26')
      }
    },
    '.t-h4': {
      fontFamily: theme('fontFamily.title'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.15'),

      [md]: {
        fontSize: theme('fontSize.18')
      },

      [xl]: {
        fontSize: theme('fontSize.20')
      }
    },
    '.t-h4-capital': {
      fontFamily: theme('fontFamily.title'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.2'),
      fontSize: theme('fontSize.15'),
      textTransform: 'uppercase',

      [md]: {
        fontSize: theme('fontSize.18')
      },

      [xl]: {
        fontSize: theme('fontSize.20')
      }
    },

    /* Style Paragraph */
    '.t-p1': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.regular'),
      lineHeight: theme('lineHeight.140'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.base'),

      [md]: {
        fontSize: theme('fontSize.20')
      },

      [xl]: {
        fontSize: theme('fontSize.24'),
        lineHeight: theme('lineHeight.150')
      }
    },
    '.t-p2': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.regular'),
      lineHeight: theme('lineHeight.140'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.14'),

      [md]: {
        fontSize: theme('fontSize.base')
      },

      [xl]: {
        fontSize: theme('fontSize.18')
      }
    },
    '.t-p3': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.regular'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.1'),
      fontSize: theme('fontSize.12'),

      [md]: {
        lineHeight: theme('lineHeight.140')
      },

      [xl]: {
        fontSize: theme('fontSize.base'),
        letterSpacing: theme('letterSpacing.0')
      }
    },

    /* Style System */
    '.t-pagination': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.regular'),
      lineHeight: theme('lineHeight.140'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.14'),

      [md]: {
        fontSize: theme('fontSize.20')
      },

      [xl]: {
        fontSize: theme('fontSize.26'),
        fontWeight: theme('fontWeight.medium'),
        lineHeight: theme('lineHeight.120')
      }
    },
    '.t-button': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.120'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.14'),

      [md]: {
        lineHeight: theme('lineHeight.140'),
        fontSize: theme('fontSize.base')
      },

      [xl]: {
        fontSize: theme('fontSize.18'),
        lineHeight: theme('lineHeight.120')
      }
    },
    '.t-tag': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.regular'),
      lineHeight: theme('lineHeight.140'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.10'),

      [md]: {
        letterSpacing: theme('letterSpacing.1'),
        fontSize: theme('fontSize.12')
      },

      [xl]: {
        letterSpacing: theme('letterSpacing.0'),
        fontSize: theme('fontSize.base')
      }
    },
    '.t-caption': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.regular'),
      lineHeight: theme('lineHeight.130'),
      letterSpacing: theme('letterSpacing.1'),
      fontSize: theme('fontSize.10'),

      [md]: {
        fontSize: theme('fontSize.12')
      }
    },
    '.t-breadcrumb': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.regular'),
      lineHeight: theme('lineHeight.120'),
      letterSpacing: theme('letterSpacing.0'),
      fontSize: theme('fontSize.12')
    },
    '.t-date': {
      fontFamily: theme('fontFamily.text'),
      fontWeight: theme('fontWeight.semibold'),
      lineHeight: theme('lineHeight.120'),
      letterSpacing: theme('letterSpacing.3'),
      textTransform: 'uppercase',
      fontSize: theme('fontSize.12'),

      [md]: {
        fontSize: theme('fontSize.14')
      },

      [xl]: {
        fontSize: theme('fontSize.base')
      }
    }
  })
})
