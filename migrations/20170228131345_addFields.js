exports.up = function(knex, Promise) {
  return Promise.all([
    knex.schema.table('teampit', function (table) {
      table.integer('ballCapacity').notNullable();
      table.boolean('abilityToAddClimber').notNullable();
    }),
    knex.schema.table('matchballfeeds_preprocess', function (table) {
      table.integer('count').nullable();
    })
  ]);

};

exports.down = function(knex, Promise) {
  return Promise.all([
    knex.schema.table('teampit', function (table) {
      table.drop('ballCapacity');
      table.drop('abilityToAddClimber');
    }),
    knex.schema.table('matchballfeeds_preprocess', function (table) {
      table.drop('count');
    })
  ]);
};
