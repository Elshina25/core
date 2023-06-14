module.exports = {
  '**/*.{js,ts,vue,css}': 'npm run prettier:fix',
  '**/*.{js,ts,vue}': 'npm run eslint:fix',
  '**/*.{ts,vue}': () => 'npm run type-checking'
}
